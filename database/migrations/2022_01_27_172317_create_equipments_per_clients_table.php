<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentsPerClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments_per_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId("client_id")->constrained();
            $table->unsignedBigInteger("equipment_id");
            $table->foreignId("pqr_id")->nullable()->constrained();
            $table->boolean("active");
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
        Schema::dropIfExists('equipments_per_clients');
    }
}
