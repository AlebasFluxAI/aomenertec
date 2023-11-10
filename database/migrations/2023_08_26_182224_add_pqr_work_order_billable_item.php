<?php

use App\Models\V1\BillableItem;
use App\Models\V1\Tax;
use Illuminate\Database\Migrations\Migration;

class AddPqrWorkOrderBillableItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        BillableItem::create([
            "name" => "Peticiones gestionadas",
            "slug" => BillableItem::PQR_ISSUED,
            "description" => "Peticiones gestionadas",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Ordenes de trabajo gestionadas",
            "slug" => BillableItem::WORK_ORDER,
            "description" => "Ordenes de trabajo gestionadas",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Peticiones gestionadas bolsa inicial",
            "slug" => BillableItem::PQR_ISSUED_INITIAL,
            "description" => "Peticiones gestionadas bolsa inicial",
            "tax_id" => Tax::wherePercentage(0)->first()->id
        ]);

        BillableItem::create([
            "name" => "Ordenes de trabajo gestionadas bolsa inicial",
            "slug" => BillableItem::WORK_ORDER_INITIAL,
            "description" => "Ordenes de trabajo gestionadas bolsa inicial",
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
        //
    }
}
