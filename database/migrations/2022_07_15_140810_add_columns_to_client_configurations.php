<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToClientConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_configurations', function (Blueprint $table) {
            $table->enum("storage_type_latency", [
                \App\Models\V1\ClientConfiguration::STORAGE_LATENCY_TYPE_DAILY,
                \App\Models\V1\ClientConfiguration::STORAGE_LATENCY_TYPE_HOURLY,
                \App\Models\V1\ClientConfiguration::STORAGE_LATENCY_TYPE_MONTHLY
            ])->default(\App\Models\V1\ClientConfiguration::STORAGE_LATENCY_TYPE_HOURLY);
            $table->enum("connection_type", [
                \App\Models\V1\ClientConfiguration::CONECTION_TYPE_4G,
                \App\Models\V1\ClientConfiguration::CONECTION_TYPE_OTHERS,
            ])->default(\App\Models\V1\ClientConfiguration::CONECTION_TYPE_OTHERS);
            $table->boolean("active_real_time")->default(false);
            $table->enum('frame_type', [
                \App\Models\V1\ClientConfiguration::FRAME_TYPE_ACTIVE_ENERGY,
                \App\Models\V1\ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY,
                \App\Models\V1\ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES,
            ])->default(\App\Models\V1\ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_configurations', function (Blueprint $table) {
            $table->dropColumn("storage_type_latency");
            $table->dropColumn("connection_type");
            $table->dropColumn("active_real_time");
            $table->dropColumn("frame_type");
        });
    }
}
