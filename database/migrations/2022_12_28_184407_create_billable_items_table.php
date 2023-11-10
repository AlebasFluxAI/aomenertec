<?php

use App\Models\V1\BillableItem;
use App\Models\V1\Tax;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillableItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billable_items', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->text("description")->nullable();
            $table->text("code")->nullable();
            $table->foreignId("tax_id")->default(1)->constrained();
            $table->softDeletes();
            $table->timestamps();
        });

        BillableItem::create([
            "name" => "Clientes en plataforma (Clientes activos)",
            "description" => "Costos por el monitoreo de clientes dentro de plataforma",
            "tax_id" => Tax::where("percentage", 0)->first()->id
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billable_items');
    }
}
