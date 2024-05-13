<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->unsignedBigInteger("microcontroller_data_id");
            $table->foreignId('client_alert_configuration_id')->constrained();
            $table->double('value');
            $table->enum('type', [
                \App\Models\V1\ClientAlert::ALERT,
                \App\Models\V1\ClientAlert::CONTROL,
                \App\Models\V1\ClientAlert::INFORMATIVE,
            ])->default(\App\Models\V1\ClientAlert::ALERT);
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
        Schema::dropIfExists('client_alerts');
    }
}
