<?php

namespace App\Jobs\V1\Enertec;

use App\Http\Resources\V1\Icon;
use App\Models\V1\BillableItem;
use App\Models\V1\Client;
use App\Models\V1\ClientType;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
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
        $invoice = $client->invoices()->create();

        if (ClientType::find($clientType)->type == ClientType::SIN_CONVENTIONAL) {
            $otherFee = $networkOperator->sinOtherFees()->whereStrataId($stratum->id)->first();
            $discount = $otherFee->discount;
            $contribution = $otherFee->contribution;
            $publicTaxType = $otherFee->tax_type;
            $publicTax = $otherFee->tax;
        } else {
            $otherFee = $networkOperator->zniOtherFees()->whereStrataId($stratum->id)->first();
            $discount = $otherFee->discount;
            $contribution = $otherFee->contribution;
            $publicTaxType = $otherFee->tax_type;
            $publicTax = $otherFee->tax;
        }

        $total_consumption = $invoice->client->monthlyMicrocontrollerData()
            ->where("month", now()->subMonths(2)->month)
            ->where("year", now()->year)
            ->first()
            ->interval_real_consumption;

        $consumptionFee = $invoice->client->consumptionFee();
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
        $consumption = $invoice->client->consumption();
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
                "unit_total" => $item,
                "subtotal" => $item,
                "total" => $item,
                "tax_total" => 0.0,
                "discount" => 0.0,
                "billable_item_id" => BillableItem::whereSlug($key)->first()->id,
                "quantity" => 1,
            ]);

        }

        $pdf = Pdf::loadView("mail.v1.client_invoice_pdf", [
            "invoice" => $invoice,
            "consumption" => $invoice->client->consumption(),
            "client" => $client
        ]);
        $pdf->setPaper('LETTER');
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
