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
            $table->string("measure_type")->nullable();//Topologia:   1-monofasico, 2-bifasico, 3-trifasico
            $table->string("mqtt_host")->nullable();//Direccion del broker
            $table->string("mqtt_port")->nullable();//puerto del broker
            $table->string("mqtt_user")->nullable();//ususario MQTT
            $table->string("mqtt_password")->nullable();//Contraseña MQTT
            $table->boolean("real_time_flag")->nullable();//Bandera para iniciar el tiempo real
            $table->double("max_adc_1")->nullable();//Maximo de lectura voltaje dc ADC 1
            $table->double("min_adc_1")->nullable();//minimo de lectura voltaje dc ADC 1
            $table->double("max_adc_2")->nullable();//Maximo de lectura voltaje dc ADC 2
            $table->double("min_adc_2")->nullable();//minimo de lectura voltaje dc ADC 2
            $table->double("max_vol_ph_1")->nullable();//maximo de voltaje de la fase 1 a neutro
            $table->double("min_vol_ph_1")->nullable();//minimo de voltaje de la fase 1 a neutro
            $table->double("max_vol_ph_2")->nullable();//maximo de voltaje de la fase 2 a neutro
            $table->double("min_vol_ph_2")->nullable();//minimo de voltaje de la fase 2 a neutro
            $table->double("max_vol_ph_3")->nullable();//maximo de voltaje de la fase 3 a neutro3
            $table->double("min_vol_ph_3")->nullable();//minimo de voltaje de la fase 3 a neutro

            $table->double("max_current_ph_1")->nullable();//maximo de corriente de la fase 1 a neutro
            $table->double("min_current_ph_1")->nullable();//minimo de corriente de la fase 1 a neutro\
            $table->double("max_current_ph_2")->nullable();//maximo de corriente de la fase 2 a neutro
            $table->double("min_current_ph_2")->nullable();//minimo de corriente de la fase 2 a neutro
            $table->double("max_current_ph_3")->nullable();//maximo de corriente de la fase 3 a neutro
            $table->double("min_current_ph_3")->nullable();//minimo de corriente de la fase 3 a neutro

            $table->double("max_power_ph_1")->nullable();//maximo de potencia de la fase 1 a neutro
            $table->double("min_power_ph_1")->nullable();//minimo de potencia de la fase 1 a neutro\
            $table->double("max_power_ph_2")->nullable();//maximo de potencia de la fase 2 a neutro
            $table->double("min_power_ph_2")->nullable();//minimo de potencia de la fase 2 a neutro
            $table->double("max_power_ph_3")->nullable();//maximo de potencia de la fase 3 a neutro
            $table->double("min_power_ph_3")->nullable();//minimo de potencia de la fase 3 a neutro

            $table->double("max_va_ph_1")->nullable();//maximo de volts ampers de la fase 1 a neutro
            $table->double("min_va_ph_1")->nullable();//minimo de volts ampers de la fase 1 a neutro\
            $table->double("max_va_ph_2")->nullable();//maximo de volts ampers de la fase 2 a neutro
            $table->double("min_va_ph_2")->nullable();//minimo de volts ampers de la fase 2 a neutro
            $table->double("max_va_ph_3")->nullable();//maximo de volts ampers de la fase 3 a neutro
            $table->double("min_va_ph_3")->nullable();//minimo de volts ampers de la fase 3 a neutro

            $table->double("max_var_ph_1")->nullable();//maximo de volts ampers reactivos de la fase 1 a neutro
            $table->double("min_var_ph_1")->nullable();//minimo de volts ampers reactivos de la fase 1 a neutro\
            $table->double("max_var_ph_2")->nullable();//maximo de volts ampers reactivos de la fase 2 a neutro
            $table->double("min_var_ph_2")->nullable();//minimo de volts ampers reactivos de la fase 2 a neutro
            $table->double("max_var_ph_3")->nullable();//maximo de volts ampers reactivos de la fase 3 a neutro
            $table->double("min_var_ph_3")->nullable();//minimo de volts ampers reactivos de la fase 3 a neutro


            $table->double("max_pfp_ph_1")->nullable();//maximo de factor de potencia de la fase 1 a neutro
            $table->double("min_pfp_ph_1")->nullable();//minimo de factor de potencia de la fase 1 a neutro\
            $table->double("max_pfp_ph_2")->nullable();//maximo de factor de potencia de la fase 2 a neutro
            $table->double("min_pfp_ph_2")->nullable();//minimo de factor de potencia de la fase 2 a neutro
            $table->double("max_pfp_ph_3")->nullable();//maximo de factor de potencia de la fase 3 a neutro
            $table->double("min_pfp_ph_3")->nullable();//minimo de factor de potencia de la fase 3 a neutro

            $table->double("max_freq")->nullable();//maxima de frecuencia
            $table->double("min_freq")->nullable();//minimo de frecuencia

            $table->double("flag_wh_import")->nullable();//bandera para marcar un valor alertable cuando el acumulado de energia consumida supere dicho valor
            $table->double("flag_wh_export")->nullable();//bandera para marcar un valor alertable cuando el acumulado de energia exportada supere dicho valor


            $table->double("flag_wh_import_varh")->nullable();//bandera para marcar un valor alertable cuando el acumulado de energia reactiva consumida supere dicho valor
            $table->double("flag_wh_export_varh")->nullable();//bandera para marcar un valor alertable cuando el acumulado de energia reactiva exportada supere dicho valor

            $table->double("max_volt_1_2")->nullable();//maximo de voltaje entre fase 1 y fase 2
            $table->double("min_volt_1_2")->nullable();//minimo de voltaje entre fase 1 y fase 2

            $table->double("max_volt_3_1")->nullable();//maximo de voltaje entre fase 3 y fase 1
            $table->double("min_volt_3_1")->nullable();//minimo de voltaje entre fase 3 y fase 1

            $table->double("max_volt_2_3")->nullable();//maximo de voltaje entre fase 2 y fase 3
            $table->double("min_volt_2_3")->nullable();//minimo de voltaje entre fase 2 y fase 3


            $table->double("max_vthd_ph_1")->nullable();//maximo THD de voltaje en fase 1
            $table->double("min_vthd_ph_1")->nullable();//minimo THD de voltaje en fase 1

            $table->double("max_vthd_ph_2")->nullable();//maximo THD de voltaje en fase 2
            $table->double("min_vthd_ph_2")->nullable();//minimo THD de voltaje en fase 2

            $table->double("max_vthd_ph_3")->nullable();//maximo THD de voltaje en fase 3
            $table->double("min_vthd_ph_3")->nullable();//minimo THD de voltaje en fase 3


            $table->double("max_cthd_ph_1")->nullable();//maximo THD de corriente en fase 1
            $table->double("min_cthd_ph_1")->nullable();//minimo THD de corriente en fase 1

            $table->double("max_cthd_ph_2")->nullable();//maximo THD de corriente en fase 2
            $table->double("min_cthd_ph_2")->nullable();//minimo THD de corriente en fase 2

            $table->double("max_cthd_ph_3")->nullable();//maximo THD de corriente en fase 3
            $table->double("min_cthd_ph_3")->nullable();//minimo THD de corriente en fase 3

            $table->double("max_vthd_ph_1_2")->nullable();//maximo THD de voltaje entre fase 1 y fase 2
            $table->double("min_vthd_ph_1_2")->nullable();//minimo THD de voltaje entre fase 1 y fase 2

            $table->double("max_vthd_ph_2_3")->nullable();//maximo THD de voltaje entre fase 2 y fase 3
            $table->double("min_vthd_ph_2_3")->nullable();//minimo THD de voltaje entre fase 2 y fase 3


            $table->double("max_vthd_ph_3_1")->nullable();//maximo THD de voltaje entre fase 3 y fase 1
            $table->double("min_vthd_ph_3_1")->nullable();//minimo THD de voltaje entre fase 3 y fase 1


            $table->double("real_time_latency")->nullable();//Tiempo de muestreo en tiempo real
            $table->double("storage_latency")->nullable();//Tiempo de muestreo para monitorear normalmente
            $table->double("reading_latency")->nullable();//Tiempo de muestro de lectura para promedios

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
