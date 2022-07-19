<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Resources\V1\AlterEnumHelper;
use App\Models\V1\Equipment;

class AddRepairPendingToEquipments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AlterEnumHelper::alterEnum(new Equipment(), "status", [
            Equipment::STATUS_NEW,
            Equipment::STATUS_REPAIRED,
            Equipment::STATUS_REPAIR,
            Equipment::STATUS_DISREPAIR,
            Equipment::STATUS_REPAIR_PENDING
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipments', function (Blueprint $table) {
            //
        });
    }
}
