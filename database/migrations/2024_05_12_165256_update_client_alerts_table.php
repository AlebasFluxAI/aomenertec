<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_alerts', function (Blueprint $table) {
            $table->unsignedBigInteger('client_alert_configuration_id')->nullable()->change();
            $table->unsignedBigInteger('microcontroller_Data_id')->nullable()->change();
            $table->float('value')->nullable()->change();
            $table->foreignId('event_log_id')->nullable()->constrained();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_alerts', function (Blueprint $table) {
            $table->dropColumn('event_log_id');
        });
    }
}
