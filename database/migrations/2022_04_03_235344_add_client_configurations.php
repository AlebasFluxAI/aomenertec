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
            $table->boolean("real_time_flag")->nullable();//Bandera para iniciar el tiempo real
            $table->double("real_time_latency")->nullable();//Tiempo de muestreo en tiempo real
            $table->double("storage_latency")->nullable();//Tiempo de muestreo para monitorear normalmente
            $table->double("digital_outputs")->nullable();//Tiempo de muestreo para monitorear normalmente
            $table->timestamps();
            $table->softDeletes();
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
