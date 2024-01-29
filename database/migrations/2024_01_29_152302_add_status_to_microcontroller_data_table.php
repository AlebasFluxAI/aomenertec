<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToMicrocontrollerDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('microcontroller_data', function (Blueprint $table) {
            $table->enum("status", [
                \App\Models\V1\MicrocontrollerData::PENDING_TIMESTAMP,
                \App\Models\V1\MicrocontrollerData::SUCCESS_TIMESTAMP,
                \App\Models\V1\MicrocontrollerData::PENDING_UNPACK,
                \App\Models\V1\MicrocontrollerData::SUCCESS_UNPACK,
                \App\Models\V1\MicrocontrollerData::PENDING_REORDER,
                \App\Models\V1\MicrocontrollerData::PROCESING_TIMESTAMP,
            ])->nullable()->default(\App\Models\V1\MicrocontrollerData::PENDING_TIMESTAMP);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('microcontroller_data', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
