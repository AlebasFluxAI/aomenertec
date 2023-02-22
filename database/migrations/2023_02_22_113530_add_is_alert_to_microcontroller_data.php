<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAlertToMicrocontrollerData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('microcontroller_data', function (Blueprint $table) {
            $table->boolean("is_alert")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('microcontroller_data', function (Blueprint $table) {
            $table->dropColumn("is_alert");
        });
    }
}
