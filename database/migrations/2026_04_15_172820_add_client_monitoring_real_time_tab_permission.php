<?php

use App\Models\V1\TabPermission;
use Illuminate\Database\Migrations\Migration;

class AddClientMonitoringRealTimeTabPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        TabPermission::firstOrCreate(
            ["permission" => TabPermission::CLIENT_MONITORING_REAL_TIME],
            ["details" => "Permiso para tab de monitoreo en tiempo real del cliente"]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        TabPermission::wherePermission(TabPermission::CLIENT_MONITORING_REAL_TIME)->delete();
    }
}
