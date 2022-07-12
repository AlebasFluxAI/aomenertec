<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFrameTypeToAdminConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_configurations', function (Blueprint $table) {
            $table->enum('frame_type', [
                \App\Models\V1\AdminConfiguration::FRAME_TYPE_ACTIVE_ENERGY,
                \App\Models\V1\AdminConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY,
                \App\Models\V1\AdminConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES,
            ])->default(\App\Models\V1\AdminConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('admin_configurations', function (Blueprint $table) {
            $table->dropColumn("frame_type");
        });
    }
}
