<?php

use App\Http\Resources\V1\AlterEnumHelper;
use App\Models\V1\WorkOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeDisableClientToWorkOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AlterEnumHelper::alterEnum(new WorkOrder(), "type", [
            WorkOrder::WORK_ORDER_TYPE_REPLACE,
            WorkOrder::WORK_ORDER_TYPE_INSTALLATION,
            WorkOrder::WORK_ORDER_TYPE_CORRECTIVE_MAINTENANCE,
            WorkOrder::WORK_ORDER_TYPE_PREVENTIVE_MAINTENANCE,
            WorkOrder::WORK_ORDER_TYPE_DISABLE_CLIENT,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            //
        });
    }
}
