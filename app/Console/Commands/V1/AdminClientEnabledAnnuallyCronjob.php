<?php

namespace App\Console\Commands\V1;


use App\Models\V1\Admin;
use App\Models\V1\BillableItem;
use App\Models\V1\Client;
use App\Models\V1\Invoice;
use Illuminate\Console\Command;

class AdminClientEnabledAnnuallyCronjob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:admin_client_enabled_annually';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera factura de cobro por clientes activos para administradores';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (Admin::where("annually_client_invoicing_month", now()->month)->get() as $admin) {

            $client_count = Client::whereIn("network_operator_id", $admin->networkOperators->pluck("id"))->count();
            $client_cost = $admin->annually_client_cost;
            $invoice = $admin->invoices()->create([
                "type" => Invoice::TYPE_PLATFORM_USAGE,
            ]);
            $billableItem = BillableItem::whereSlug("costo_por_clientes_activos_anual")->first();
            $tax_value = $client_cost * $billableItem->tax->percentage / 100;
            $invoice->items()->create([
                "unit_total" => $client_cost,
                "subtotal" => $client_cost,
                "total" => ($client_cost * $client_count) + $tax_value,
                "tax_total" => $tax_value,
                "discount" => 0,
                "billable_item_id" => $billableItem->id,
                "quantity" => $client_count,
                "notes"
            ]);
            $invoice->update([
                "subtotal" => ($client_cost * $client_count),
                "total" => ($client_cost * $client_count) + $tax_value,
                "tax_total" => $tax_value,
            ]);

        }

    }


}
