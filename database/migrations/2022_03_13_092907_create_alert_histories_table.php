<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("microcontroller_data_id");
            $table->integer("flag_index");
            $table->double("value");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("microcontroller_data_id")
                ->references("id")
                ->on("microcontroller_data");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_histories');
    }
}
