<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientAlertConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_alert_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->integer('flag_id');
            $table->double('min_alert');
            $table->double('max_alert');
            $table->double('min_control');
            $table->double('max_control');
            $table->boolean('active_control');
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
        Schema::dropIfExists('client_alert_configurations');
    }
}
