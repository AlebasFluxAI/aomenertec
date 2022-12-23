<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusControlToClientDigitalOutputAlertConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_digital_output_alert_configurations', function (Blueprint $table) {
            $table->enum("control_status", [
                \App\Models\V1\ClientDigitalOutputAlertConfiguration::ON,
                \App\Models\V1\ClientDigitalOutputAlertConfiguration::OFF,
                \App\Models\V1\ClientDigitalOutputAlertConfiguration::CHANGE,
            ])->default(\App\Models\V1\ClientDigitalOutputAlertConfiguration::CHANGE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_digital_output_alert_configurations', function (Blueprint $table) {
            //
        });
    }
}
