<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHourlyMicrocontrollerDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hourly_microcontroller_data', function (Blueprint $table) {
            $table->id();
            $table->string("year");
            $table->string("month");
            $table->string("day");
            $table->string("hour");
            $table->string("minute");
            $table->foreignId('client_id')->constrained();
            $table->unsignedBigInteger("microcontroller_data_id");
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
        Schema::dropIfExists('hourly_microcontroller_data');
    }
}
