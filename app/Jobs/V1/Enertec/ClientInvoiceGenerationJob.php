<?php

namespace App\Jobs\V1\Enertec;

use App\Http\Resources\V1\Icon;
use App\Models\V1\BillableItem;
use App\Models\V1\Client;
use App\Models\V1\ClientType;
use App\Models\V1\Invoice;
use App\Models\V1\SinOtherFee;
use App\Models\V1\SubsistenceConsumption;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

class ClientInvoiceGenerationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $model;
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = $this->client;
        $publicLightTaxFlag = $client->public_lighting_tax;
        $stratum = $client->stratum;
        $networkOperator = $client->networkOperator;
        $fechaActual = Carbon::now();
        $clientType = $client->client_type_id;
        $invoice = $client->invoices()->create([
            "type" => Invoice::TYPE_CONSUMPTION,
            "network_operator_id" => $networkOperator->id
        ]);

        if (ClientType::find($clientType)->type == ClientType::SIN_CONVENTIONAL) {
            $otherFee = $networkOperator->sinOtherFees()->whereStrataId($stratum->id)->where('month', str_pad($fechaActual->copy()->subDay()->format('m'), 2, "0", STR_PAD_LEFT))->where('year', $fechaActual->copy()->subDay()->format('Y'))->first();
            if (!($otherFee)) {
                $otherFee = $networkOperator->sinOtherFees()->whereStrataId($stratum->id)->first();
            }
            $otherFee = $networkOperator->sinOtherFees()->whereStrataId($stratum->id)->first();
            $discount = $otherFee ? $otherFee->discount : 0.0;
            $contribution = $otherFee ? $otherFee->contribution : 0.0;
            $publicTaxType = $otherFee ? $otherFee->tax_type : 0.0;
            $publicTax = $otherFee ? $otherFee->tax : 0.0;
        } else {
            $otherFee = $networkOperator->zniOtherFees()->whereStrataId($stratum->id)->where('month', str_pad($fechaActual->copy()->subDay()->format('m'), 2, "0", STR_PAD_LEFT))->where('year', $fechaActual->copy()->subDay()->format('Y'))->first();
            if (!($otherFee)) {
                $otherFee = $networkOperator->zniOtherFees()->whereStrataId($stratum->id)->first();
            }
            $discount = $otherFee ? $otherFee->discount : 0.0;
            $contribution = $otherFee ? $otherFee->contribution : 0.0;
            $publicTaxType = $otherFee ? $otherFee->tax_type : 0.0;
            $publicTax = $otherFee ? $otherFee->tax : 0.0;
        }
        $billing_date = Carbon::now()->subday();
        $year = $billing_date->format('Y');
        $month = $billing_date->format('m');

        $total_consumption = $invoice->client->monthlyMicrocontrollerData()
            ->where("month", str_pad($month, 2, "0", STR_PAD_LEFT))
            ->where("year", $year)
            ->first()
            ->interval_real_consumption;
        $consumptionFee = $invoice->client->consumptionFee($year, $month);
        $totalConsumptionBase = $consumptionFee * $total_consumption;
        $totalConsumption = $totalConsumptionBase;
        $item = $invoice->items()->create([
            "unit_total" => $consumptionFee,
            "subtotal" => $totalConsumption,
            "total" => $totalConsumption,
            "tax_total" => 0.0,
            "discount" => 0.0,
            "billable_item_id" => BillableItem::whereSlug(BillableItem::TOTAL_CONSUMPTION)->first()->id,
            "quantity" => $total_consumption,
        ]);
        $consumption = $invoice->client->consumption($year, $month);

        $fees = $client->feesDate($month, $year);
        $other_fees = $client->otherFeesDate($month, $year);
        $monthly_data = $client->monthlyMicrocontrollerData()
            ->where("month", str_pad($month, 2, "0", STR_PAD_LEFT))
            ->where("year", $year)->first();
        $json = json_decode($monthly_data->raw_json);

        $sc = SubsistenceConsumption::find($client->subsistence_consumption_id);
        $value_kwh = (($fees->optional_fee == 0) ? $fees->unit_cost : $fees->optional_fee);
        $value_discount_kwh = $value_kwh * $other_fees->discount / 100;
        $value_discount = ($monthly_data->interval_real_consumption > $sc->value) ? ($sc->value * $value_discount_kwh * (-1)) : ($monthly_data->interval_real_consumption * $value_discount_kwh * (-1));
        $value_kwh = (($fees->optional_fee == 0) ? $fees->unit_cost : $fees->optional_fee);
        $value_active = $monthly_data->interval_real_consumption * $value_kwh;
        $value_tax = ($client->public_lighting_tax) ? (($other_fees->tax_type == SinOtherFee::MONEY_FEE) ? $other_fees->tax : $value_active * $other_fees->tax / 100) : 0;
        $value_kwh = (($fees->optional_fee == 0) ? $fees->unit_cost : $fees->optional_fee);
        $value_active = $monthly_data->interval_real_consumption * $value_kwh;
        $value = collect([]);
        $value->value_active = $value_active;
        $value->value_contribution = ($client->stratum->id > 4) ? (($client->contribution && $other_fees->contribution > 0) ? $value_active * $other_fees->contribution / 100 : 0) : 0;
        $value->value_discount = ($client->stratum->id < 4) ? (($other_fees->discount > 0) ? $value_discount : 0) : 0;
        $value->value_tax = $value_tax;
        $value->value_varch = ($this->client->stratum->acronym == 'COM' or $this->client->stratum->acronym == 'IND') ? ($this->fees->distribution * $monthly_data->interval_reactive_capacitive_consumption) : 0;
        $value->value_varlh = ($this->client->stratum->acronym == 'COM' or $this->client->stratum->acronym == 'IND') ? ($this->fees->distribution * $monthly_data->penalizable_reactive_inductivo_consumption) : 0;
        $value->subtotal_energy = $value->value_active + $value->value_contribution + $value->value_discount + $value->value_tax + $value->value_varch + $value->value_varlh;
        $value->subtotal_others = 0;
        $value->total = $value->subtotal_energy + $value->subtotal_others;

        foreach ([
                     BillableItem::DISTRIBUTION_ITEM => $consumption->distribution,
                     BillableItem::TRANSMISSION_ITEM => $consumption->transmission,
                     BillableItem::GENERATION_ITEM => $consumption->generation,
                     BillableItem::LOST_ITEM => $consumption->lost,
                     BillableItem::RESTRICTION_ITEM => $consumption->restriction,
                     BillableItem::COMMERCIALIZATION_ITEM => $consumption->commercialization,
                     BillableItem::DISCOUNT_ITEM => $value->value_discount,
                     BillableItem::CONTRIBUTION_ITEM => $totalContribution ?? 0,
                     BillableItem::PUBLIC_TAX_ITEM => $publicTax ?? 0,
                     BillableItem::PUBLIC_TAX_TYPE_TOTAL => $publicTaxTotal ?? 0,
                     BillableItem::TOTAL_WITH_SUB => $value->total,
                     BillableItem::TOTAL_WITHOUT_SUB => $value->total,
                     BillableItem::TOTAL_CONSUMPTION_BASE => $totalConsumptionBase,
                     BillableItem::TOTAL_INVOICE => $value->total,
                 ]
                 as $key => $item) {
            $invoice->items()->create([
                "unit_total" => $item ?? 0.0,
                "subtotal" => $item ?? 0.0,
                "total" => $item ?? 0.0,
                "tax_total" => 0.0,
                "discount" => 0.0,
                "billable_item_id" => BillableItem::whereSlug($key)->first()->id,
                "quantity" => 1,
            ]);

        }


        $monthly_data = $client->monthlyMicrocontrollerData()
            ->where("month", str_pad($month, 2, "0", STR_PAD_LEFT))
            ->where("year", $year)->first();

        $value_chart = ['series' => [], 'x_axis' => []];
        $promedio = 0;
        $last_month = 0;
        $date = Carbon::create($year, $month);
        $i = 0;
        while (true) {
            $data = $client->monthlyMicrocontrollerData()
                ->where("month", str_pad($date->format('m'), 2, "0", STR_PAD_LEFT))
                ->where("year", $date->format('Y'))->first();
            if ($data) {
                array_push($value_chart['series'], round($data->interval_real_consumption, 2));
                array_push($value_chart['x_axis'], Carbon::create($data->year, $data->month, $data->day)->format('d M y'));
                if ($i == 1) {
                    $last_month = $data->interval_real_consumption;
                    $date_last_month = Carbon::create($data->microcontrollerData->source_timestamp);
                }
                if ($i == 0) {
                    $month = $data->interval_real_consumption;
                    $date_month = Carbon::create($data->microcontrollerData->source_timestamp);
                }
                $promedio = $data->interval_real_consumption + $promedio;

            } else {
                array_push($value_chart['series'], 0);
                array_push($value_chart['x_axis'], $date->format('M y'));
                if ($i == 1) {
                    $last_month = 0;
                    $date_last_month = null;
                }
                if ($i == 0) {
                    $month = 0;
                    $date_month = null;
                }
            }

            if ($i == 5) {
                break;
            }
            $i++;
            $date->subMonthNoOverflow();
        }

        $chartConfig = "{
              'type': 'bar',
              'data': {
                'labels': " . json_encode($value_chart['x_axis']) . ",
                'datasets': [{
                  'label': 'Historicos de consumo (Kwh)',
                  'data': " . json_encode($value_chart['series']) . "
                }]
              }
            }";
        $chartUrl = 'https://quickchart.io/chart?w=500&h=300&c=' . urlencode($chartConfig);
        $client = Client::find($client->id);
        $promedio = $promedio / 6;
        $others_data['pago_oportuno'] = $fechaActual->copy()->addDays(15)->format('Y-m-d');
        $others_data['suspension'] = $fechaActual->copy()->addDays(20)->format('Y-m-d');
        $others_data['serial_meter'] = $client->getSerialMeter();
        $others_data['promedio'] = $promedio;
        $others_data['last_month'] = $last_month;
        $others_data['periodo_facturado'] = $date_last_month == null ? $date_month->format('Y-m-d') . ' - ' . $date_month->format('Y-m-01') : $date_month->format('Y-m-d') . ' - ' . $date_last_month->format('Y-m-d');
        $others_data['dias_facturados'] = $date_last_month == null ? $date_month->format('d') : $date_month->diffInDays($date_last_month);
        $others_data['numero_factura'] = $date_month->format('y') . $date_month->format('m') . $client->code;
        $generadorDeCodigoDeBarras = new DNS1D();
        $imagenDeCodigoDeBarras = $generadorDeCodigoDeBarras->getBarcodePNG('123456789', 'C39');
        $generate_qr_code = new DNS2D();
        $qr_code = $generate_qr_code->getBarcodePNG('aom.enerteclatam.com', 'QRCODE');
        $value_aux = [
            'value_active' => $value->value_active,
            'value_contribution' => $value->value_contribution,
            'value_discount' => $value->value_discount,
            'value_tax' => $value->value_tax,
            'value_varch' => $value->value_varch,
            'value_varlh' => $value->value_varlh,
            'subtotal_energy' => $value->subtotal_energy,
            'subtotal_others' => $value->subtotal_others,
            'total' => $value->total,
        ];
        $invoice->update([
            "sub_total" => $value->total,
            "total" => $value->total,
            "tax_total" => $publicTax,
            "pdf_data" => [
                "image_chart_url" => $chartUrl,
                'value' => $value_aux,
                'json' => $json,
                'monthly_data' => $monthly_data,
                'client' => Client::find($this->client->id),
                'network_operator' => $networkOperator,
                'admin' => $networkOperator->admin,
                'fees' => $fees,
                'other_fees' => $other_fees,
                'bar_code' => $imagenDeCodigoDeBarras,
                'qr_code' => $qr_code,
                'other_data' => $others_data
            ]
        ]);
        $pdf = Pdf::loadView('reports.client_invoice', [
            "image_chart_url" => $chartUrl,
            'value' => $value,
            'json' => $json,
            'monthly_data' => $monthly_data,
            'client' => Client::find($this->client->id),
            'network_operator' => $networkOperator,
            'admin' => $networkOperator->admin,
            'fees' => $fees,
            'other_fees' => $other_fees,
            'bar_code' => $imagenDeCodigoDeBarras,
            'qr_code' => $qr_code,
            'other_data' => $others_data
        ]);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        $date = now()->format("d-m-Y");
        $content = $pdf->download()->getOriginalContent();
        $filePath = "bills/Factura_{$invoice->code}_{$date}.pdf";
        Storage::disk("public")->put($filePath, $content);

        Mail::send("mail.v1.client_invoice_email", [
            "user" => $client,
            "logo_url" => Icon::getUserIconUser($client),
        ], function ($message) use ($client, $filePath) {
            $message->to($client->email)
                ->attach((Storage::disk("public")->path($filePath)))
                ->subject("Nueva factura de consumo");
        });
    }
}
