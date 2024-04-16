<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddControlStatusToClientAlertConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_alert_configurations', function (Blueprint $table) {

            $table->enum("status_control", [
                \App\Models\V1\ClientDigitalOutputAlertConfiguration::CHANGE,
                \App\Models\V1\ClientDigitalOutputAlertConfiguration::ON,
                \App\Models\V1\ClientDigitalOutputAlertConfiguration::OFF,
            ])->nullable()->default(\App\Models\V1\ClientDigitalOutputAlertConfiguration::CHANGE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_alert_configurations', function (Blueprint $table) {
            $table->dropColumn('status_control');
        });
    }
}
