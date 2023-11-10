<?php

use App\Models\V1\BillableItem;
use App\Models\V1\Tax;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceItemsToConsuption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        BillableItem::create([
            "name" => "Contribución",
            "slug" => BillableItem::CONTRIBUTION_ITEM,
            "description" => "Contribución",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Descuento",
            "slug" => BillableItem::DISCOUNT_ITEM,
            "description" => "Descuento",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Impuesto alumbrado publico",
            "slug" => BillableItem::PUBLIC_TAX_ITEM,
            "description" => "Impuesto alumbrado publico",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);
        BillableItem::create([
            "name" => "Comercializacion",
            "slug" => BillableItem::COMMERCIALIZATION_ITEM,
            "description" => "Comercializacion",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Distribucion",
            "slug" => BillableItem::DISTRIBUTION_ITEM,
            "description" => "Distribucion",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Restriccion",
            "slug" => BillableItem::RESTRICTION_ITEM,
            "description" => "Restriccion",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Generacion",
            "slug" => BillableItem::GENERATION_ITEM,
            "description" => "Generacion",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);
        BillableItem::create([
            "name" => "Perdidas",
            "slug" => BillableItem::LOST_ITEM,
            "description" => "Perdidas",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);
        BillableItem::create([
            "name" => "Transmicion",
            "slug" => BillableItem::TRANSMISSION_ITEM,
            "description" => "Perdidas",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Consumo Total",
            "slug" => BillableItem::TOTAL_CONSUMPTION,
            "description" => "Consumo Total",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);
        BillableItem::create([
            "name" => "Impuesto publico",
            "slug" => BillableItem::PUBLIC_TAX_ITEM,
            "description" => "Impuesto publico",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);
        BillableItem::create([
            "name" => "Total de impuesto publico",
            "slug" => BillableItem::PUBLIC_TAX_TYPE_TOTAL,
            "description" => "Total de impuesto publico",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);
        BillableItem::create([
            "name" => "Tipo de impuesto publico",
            "slug" => BillableItem::PUBLIC_TAX_TYPE_ITEM,
            "description" => "Tipo de impuesto publico",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Total con subsidio",
            "slug" => BillableItem::TOTAL_WITH_SUB,
            "description" => "Total con subsidio",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Total sin subsidio",
            "slug" => BillableItem::TOTAL_WITHOUT_SUB,
            "description" => "Total sin subsidio",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);
        BillableItem::create([
            "name" => "Costo consumo total base",
            "slug" => BillableItem::TOTAL_CONSUMPTION_BASE,
            "description" => "Costo consumo total base",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);
        BillableItem::create([
            "name" => "Total de factura",
            "slug" => BillableItem::TOTAL_INVOICE,
            "description" => "Total de factura",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);


    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
