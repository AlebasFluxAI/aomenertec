<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDigitalOutputAlertConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_digital_output_alert_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_alert_configuration_id')->constrained();
            $table->foreignId('client_digital_output_id')->constrained();
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
        Schema::dropIfExists('client_digital_output_alert_configurations');
    }
}
