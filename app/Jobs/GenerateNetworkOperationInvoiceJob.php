<?php

namespace App\Jobs;

use App\Models\V1\BillableItem;
use App\Models\V1\ClientType;
use App\Models\V1\Invoice;
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
    private $month;
    private $years;

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
            if ($this->network_operator->billableServices->min_clients >= $client_quantity) {
                $invoice = $this->minCostGeneration($client_quantity);
            } else {
                $invoice = $this->regularCostGeneration($network_operator_clients->get());
            }
            $this->pqrIssued($invoice);
        });
    }

    private function pqrIssued(Invoice $invoice)
    {
        $billingService = $this->network_operator->billingService;
        $pqr_total_number = $this->network_operator->pqrs->where("created_at", ">", now()->startOfMonth())->count();
        $pqr_initial_bag = $billingService->pqr_initial_bag;
        $pqr_cost = $billingService->pqr_price;
        if (!$pqr_total_number) {
            return;
        }
        if ($pqr_initial_bag > 0) {
            $initial_package_pqr_price = $billingService->initial_package_pqr_price;
            $pqr_initial_billable_item = BillableItem::whereSlug(BillableItem::PQR_ISSUED_INITIAL)->first()->id;
            $invoice->items()->create([
                "unit_total" => $initial_package_pqr_price,
                "subtotal" => $initial_package_pqr_price,
                "total" => $initial_package_pqr_price,
                "tax_total" => 0,
                "discount" => 0,
                "quantity" => 1,
                "billable_item_id" => $pqr_initial_billable_item->id,
                "tax_percentage" => $pqr_initial_billable_item->tax->percentage,
                "notes" => "Bolsa inicial de PQR",
            ]);
            $pqr_total_number -= $pqr_initial_bag;
        }
        $pqr_initial_billable_item = BillableItem::whereSlug(BillableItem::PQR_ISSUED)->first()->id;

        $invoice->items()->create([
            "unit_total" => $pqr_cost,
            "subtotal" => $pqr_total_number * $pqr_cost,
            "total" => $pqr_total_number * $pqr_cost,
            "tax_total" => 0,
            "discount" => 0,
            "quantity" => $pqr_total_number,
            "billable_item_id" => $pqr_initial_billable_item->id,
            "tax_percentage" => $pqr_initial_billable_item->tax->percentage,
            "notes" => "Bolsa inicial de PQR",
        ]);

    }

    private function minCostGeneration($client_quantity)
    {
        $billable_item = BillableItem::first();
        $client_value = $this->network_operator->billableServices->min_client_value;
        $subtotal = $client_value * $client_quantity;
        $tax = $billable_item->tax;
        $total_tax = ($subtotal * $tax->percentage / 100);
        $total = $subtotal + $total_tax;

        $invoice = $this->network_operator->invoices()->create([
            "payment_date" => now(),
            "admin_id" => $this->network_operator->id,
            "expiration_date" => now()->addDays(5),
            "currency" => strtoupper($this->network_operator->billableServices->coin),
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
        return $invoice;
    }

    private function regularCostGeneration($admin_clients)
    {
        $billable_item = BillableItem::first();

        $adminPrices = $this->network_operator->networkOperatorClientPrices;

        $invoice = $this->network_operator->invoices()->create([
            "payment_date" => now(),
            "expiration_date" => now()->addDays(5),
            "currency" => $this->network_operator->billableServices ? strtoupper($this->network_operator->billableServices->coin) : "cop",
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
        return $invoice;

    }
}
