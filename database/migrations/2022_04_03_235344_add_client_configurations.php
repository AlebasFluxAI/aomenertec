<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("client_id");
            $table->string("ssid")->nullable();//Nombre de la red wifi a conectar
            $table->string("wifi_password")->nullable(); //Contraseña de la red wifi
            $table->string("mqtt_host")->nullable();//Direccion del broker
            $table->string("mqtt_port")->nullable();//puerto del broker
            $table->string("mqtt_user")->nullable();//ususario MQTT
            $table->string("mqtt_password")->nullable();//Contraseña MQTT
            $table->boolean("active_real_time")->default(false);
            $table->boolean("real_time_flag")->nullable();//Bandera para iniciar el tiempo real
            $table->double("real_time_latency")->nullable();//Tiempo de muestreo en tiempo real
            $table->double("storage_latency")->nullable();//Tiempo de muestreo para monitorear normalmente
            $table->enum("storage_type_latency", [
                \App\Models\V1\ClientConfiguration::STORAGE_LATENCY_TYPE_DAILY,
                \App\Models\V1\ClientConfiguration::STORAGE_LATENCY_TYPE_HOURLY,
                \App\Models\V1\ClientConfiguration::STORAGE_LATENCY_TYPE_MONTHLY
            ])->default(\App\Models\V1\ClientConfiguration::STORAGE_LATENCY_TYPE_HOURLY);
            $table->double("digital_outputs")->nullable();//Tiempo de muestreo para monitorear normalmente
            $table->enum("connection_type", [
                \App\Models\V1\ClientConfiguration::CONECTION_TYPE_4G,
                \App\Models\V1\ClientConfiguration::CONECTION_TYPE_OTHERS,
            ])->default(\App\Models\V1\ClientConfiguration::CONECTION_TYPE_OTHERS);
            $table->enum('frame_type', [
                \App\Models\V1\ClientConfiguration::FRAME_TYPE_ACTIVE_ENERGY,
                \App\Models\V1\ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY,
                \App\Models\V1\ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES,
            ])->default(\App\Models\V1\ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES);
            $table->integer('billing_day');
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
        Schema::dropIfExists("client_configurations");
    }
}
