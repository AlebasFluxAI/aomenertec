<?php

use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\TabPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTapPermissionsToAdminNetworkOperator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tabPermissionId = TabPermission::wherePermission(TabPermission::CLIENT_BILLING_CONFIG)->first()->id;
        foreach (Admin::get() as $admin) {
            $admin->tabPermissions()->create([
                "tab_permission_id" => $tabPermissionId
            ]);
        }

        foreach (NetworkOperator::get() as $netOperator) {
            $netOperator->tabPermissions()->create([
                "tab_permission_id" => $tabPermissionId
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_network_operator', function (Blueprint $table) {
            //
        });
    }
}
