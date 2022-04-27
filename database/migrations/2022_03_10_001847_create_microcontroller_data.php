<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMicrocontrollerData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('microcontroller_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId("client_id")->nullable()->constrained();
            $table->text("raw_json");
            $table->dateTime("source_timestamp")->nullable();
            $table->double("accumulated_real_consumption")->nullable();
            $table->double("interval_real_consumption")->nullable();
            $table->double("accumulated_reactive_consumption")->nullable();
            $table->double("interval_reactive_consumption")->nullable();
            $table->double("accumulated_reactive_capacitive_consumption")->nullable();
            $table->double("interval_reactive_capacitive_consumption")->nullable();
            $table->double("accumulated_reactive_inductive_consumption")->nullable();
            $table->double("interval_reactive_inductive_consumption")->nullable();
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
        Schema::dropIfExists('microcontroller_data');
    }
}
