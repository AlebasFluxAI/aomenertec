<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentFailuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_failures', function (Blueprint $table) {
            $table->id();
            $table->foreignId("pqr_id")->constrained();
            $table->unsignedBigInteger("equipment_id");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('equipment_id')
                ->references('id')
                ->on('equipments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_failures');
    }
}
