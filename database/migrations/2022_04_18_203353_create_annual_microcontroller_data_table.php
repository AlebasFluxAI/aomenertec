<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnualMicrocontrollerDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annual_microcontroller_data', function (Blueprint $table) {
            $table->id();
            $table->string("year");
            $table->string("month");
            $table->foreignId('client_id')->constrained();
            $table->unsignedBigInteger("microcontroller_data_id")->nullable();
            $table->double("interval_real_consumption")->nullable();
            $table->double("interval_reactive_capacitive_consumption")->nullable();
            $table->double("interval_reactive_inductive_consumption")->nullable();
            $table->double('penalizable_reactive_capacitive_consumption')->nullable();
            $table->double('penalizable_reactive_inductive_consumption')->nullable();
            $table->text("raw_json")->nullable();
            $table->foreign("microcontroller_data_id")
                ->references("id")
                ->on("microcontroller_data");
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
        Schema::dropIfExists('annual_microcontroller_data');
    }
}
