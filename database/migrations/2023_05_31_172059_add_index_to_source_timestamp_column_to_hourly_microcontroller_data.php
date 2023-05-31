<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToSourceTimestampColumnToHourlyMicrocontrollerData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hourly_microcontroller_data', function (Blueprint $table) {
            $table->index('source_timestamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hourly_microcontroller_data', function (Blueprint $table) {
            $table->dropIndex(['source_timestamp']);
        });
    }
}
