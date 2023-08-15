<?php

namespace App\Jobs;

use App\Models\V1\BillableItem;
use App\Models\V1\ClientType;
use App\Models\V1\InvoiceItem;
use App\Models\V1\Tax;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GenerateNetworkOperationInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $network_operator;

    public function __construct($network_operator)
    {
        $this->network_operator = $network_operator;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $network_operator_clients = $this->network_operator->getCurrentEnabledClients();
        $client_quantity = $network_operator_clients->count();
        DB::transaction(function () use ($network_operator_clients, $client_quantity) {
            if ($this->network_operator->admin->configAdmin->min_clients >= $client_quantity) {
                $this->minCostGeneration($client_quantity);
                return;
            }
            $this->regularCostGeneration($network_operator_clients->get());
        });
    }

    private function minCostGeneration($client_quantity)
    {
        $billable_item = BillableItem::first();
        $client_value = $this->network_operator->admin->configAdmin->min_value;
        $subtotal = $client_value * $client_quantity;
        $tax = $billable_item->tax;
        $total_tax = ($subtotal * $tax->percentage / 100);
        $total = $subtotal + $total_tax;

        $invoice = $this->network_operator->invoices()->create([
            "payment_date" => now(),
            "admin_id" => $this->network_operator->id,
            "expiration_date" => now()->addDays(5),
            "currency" => strtoupper($this->network_operator->admin->configAdmin->coin),
        ]);
        $invoice->items()->create([
            "unit_total" => $client_value,
            "subtotal" => $client_value * $client_quantity,
            "total" => $total,
            "tax_total" => $total_tax,
            "discount" => 0,
            "quantity" => $client_quantity,
            "billable_item_id" => $billable_item->id,
            "tax_percentage" => $billable_item->tax->percentage,
            "notes" => "Monto base por cliente"
        ]);
    }

    private function regularCostGeneration($admin_clients)
    {
        $billable_item = BillableItem::first();

        $adminPrices = $this->network_operator->admin->priceAdmin;

        $invoice = $this->network_operator->invoices()->create([
            "payment_date" => now(),
            "expiration_date" => now()->addDays(5),
            "currency" => strtoupper($this->network_operator->admin->configAdmin->coin),
        ]);

        foreach ($admin_clients->groupBy("client_type_id")->all() as $clientTypeId => $clientGrouped) {
            $priceAdmin = $adminPrices->where("client_type_id", $clientTypeId)->first();
            if (!$priceAdmin) {
                continue;
            }
            $price = $priceAdmin->value;
            $client_quantity = $clientGrouped->count();
            $subtotal = $price * $client_quantity;
            $tax = $billable_item->tax;
            $total_tax = ($subtotal * $tax->percentage / 100);
            $total = $subtotal + $total_tax;

            $invoice->items()->create([
                "unit_total" => $price,
                "subtotal" => $price * $client_quantity,
                "total" => $total,
                "tax_total" => $total_tax,
                "discount" => 0,
                "quantity" => $client_quantity,
                "billable_item_id" => $billable_item->id,
                "tax_percentage" => $billable_item->tax->percentage,
                "notes" => ClientType::find($clientTypeId)->type,
            ]);
        }


    }
}
