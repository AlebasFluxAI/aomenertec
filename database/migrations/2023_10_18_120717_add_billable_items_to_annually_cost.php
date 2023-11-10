<?php

use App\Models\V1\BillableItem;
use App\Models\V1\Tax;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillableItemsToAnnuallyCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        BillableItem::create([
            "name" => "Clientes en plataforma (Clientes activos) anual",
            "slug" => "costo_por_clientes_activos_anual",
            "description" => "Costos por clientes activos anual",
            "tax_id" => Tax::where("percentage", 19)->first()->id
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('annually_cost', function (Blueprint $table) {
            //
        });
    }
}
