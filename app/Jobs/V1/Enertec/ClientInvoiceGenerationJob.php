<?php

namespace App\Jobs\V1\Enertec;

use App\Http\Resources\V1\Icon;
use App\Models\V1\BillableItem;
use App\Models\V1\Client;
use App\Models\V1\ClientType;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\Invoice;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SinOtherFee;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\ZniLevelFee;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
        $clientType = $client->client_type_id;
        $invoice = $client->invoices()->create([
            "type" => Invoice::TYPE_CONSUMPTION,
            "network_operator_id" => $networkOperator->id
        ]);

        if (ClientType::find($clientType)->type == ClientType::SIN_CONVENTIONAL) {
            $otherFee = $networkOperator->sinOtherFees()->whereStrataId($stratum->id)->first();
            $discount = $otherFee ? $otherFee->discount : 0.0;
            $contribution = $otherFee ? $otherFee->contribution : 0.0;
            $publicTaxType = $otherFee ? $otherFee->tax_type : 0.0;
            $publicTax = $otherFee ? $otherFee->tax : 0.0;
        } else {
            $otherFee = $networkOperator->zniOtherFees()->whereStrataId($stratum->id)->first();
            $discount = $otherFee ? $otherFee->discount : 0.0;
            $contribution = $otherFee ? $otherFee->contribution : 0.0;
            $publicTaxType = $otherFee ? $otherFee->tax_type : 0.0;
            $publicTax = $otherFee ? $otherFee->tax : 0.0;
        }

        $year = now()->year;
        //$month = strlen(now()->month) > 1 ? now()->month : "0" . now()->month;
        $month = 4;
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
        /// Sub
        $consumptionTotalWithoutSub = 0;
        $consumptionTotalWithSub = 0;
        if ($subsistenceConsumption = $client->subsistenceConsumption) {
            $consumptionWithoutSub = ($total_consumption - $subsistenceConsumption->value) < 0 ? 0 : $total_consumption - $subsistenceConsumption->value;
            $consumptionWithSub = $total_consumption - $consumptionWithoutSub;
            $consumptionTotalWithoutSub = $consumptionWithoutSub * $consumptionFee;
            $consumptionTotalWithSub = ($consumptionWithSub * $consumptionFee) - (($consumptionWithSub * $consumptionFee) * $discount / 100);
            $totalConsumption = $consumptionTotalWithoutSub + $consumptionTotalWithSub;
        }
        ///
        $totalContribution = 0;
        //// Contribution
        if ($client->contribution) {
            $totalContribution = $totalConsumptionBase * $contribution / 100;
        }

        /// Public tax calculation
        $publicTaxTotal = 0;

        if ($publicLightTaxFlag) {
            if ($publicTaxType == ZniLevelFee::PERCENTAGE_FEE) {
                $publicTaxTotal = ($totalConsumption * $publicTax / 100);
            } else {
                $publicTaxTotal = $totalConsumption + $publicTax;
            }
        }

        /////
        $totalDiscount = ($totalConsumptionBase * ($discount ?? 0) / 100);
        $totalInvoice = $totalConsumptionBase + $totalContribution + $publicTaxTotal - $totalDiscount;


        foreach ([
                     BillableItem::DISTRIBUTION_ITEM => $consumption->distribution,
                     BillableItem::TRANSMISSION_ITEM => $consumption->transmission,
                     BillableItem::GENERATION_ITEM => $consumption->generation,
                     BillableItem::LOST_ITEM => $consumption->lost,
                     BillableItem::RESTRICTION_ITEM => $consumption->restriction,
                     BillableItem::COMMERCIALIZATION_ITEM => $consumption->commercialization,
                     BillableItem::DISCOUNT_ITEM => $totalDiscount,
                     BillableItem::CONTRIBUTION_ITEM => $totalContribution ?? 0,
                     BillableItem::PUBLIC_TAX_ITEM => $publicTax ?? 0,
                     BillableItem::PUBLIC_TAX_TYPE_TOTAL => $publicTaxTotal ?? 0,
                     BillableItem::TOTAL_WITH_SUB => $consumptionTotalWithSub,
                     BillableItem::TOTAL_WITHOUT_SUB => $consumptionTotalWithoutSub,
                     BillableItem::TOTAL_CONSUMPTION_BASE => $totalConsumptionBase,
                     BillableItem::TOTAL_INVOICE => $totalInvoice,
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
        $value->value_varch = $fees->distribution * $monthly_data->interval_reactive_capacitive_consumption;
        $value->value_varlh = $fees->distribution * $monthly_data->penalizable_reactive_inductivo_consumption;
        $value->subtotal_energy = $value->value_active + $value->value_contribution + $value->value_discount + $value->value_tax + $value->value_varch + $value->value_varlh;
        $value->subtotal_others = 0;
        $value->total = $value->subtotal_energy + $value->subtotal_others;


        $monthly_data = $client->monthlyMicrocontrollerData()
            ->where("month", str_pad($month, 2, "0", STR_PAD_LEFT))
            ->where("year", $year)->first();

        $value_chart = ['series' => [], 'x_axis' => []];
        $date = Carbon::create($year, $month);
        $i = 0;
        while (true) {
            $data = $client->monthlyMicrocontrollerData()
                ->where("month", str_pad($date->format('m'), 2, "0", STR_PAD_LEFT))
                ->where("year", $date->format('Y'))->first();
            if ($data) {
                array_push($value_chart['series'], round($data->interval_real_consumption, 2));
                array_push($value_chart['x_axis'], Carbon::create($data->year, $data->month, $data->day)->format('d M y'));
            } else {
                array_push($value_chart['series'], 0);
                array_push($value_chart['x_axis'], $date->format('M y'));
            }
            if ($i == 5) {
                break;
            }
            $i++;
            $date->subMonth();
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
