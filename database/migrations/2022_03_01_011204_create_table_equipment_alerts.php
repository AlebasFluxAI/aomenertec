<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEquipmentAlerts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("equipments_id")->constrained();
            $table->enum("type", ["TYPE_ALERT"]);
            $table->integer("interval")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_equipment_alerts');
    }
}
