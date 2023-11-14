<?php

use App\Models\V1\TabPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPermissionToTap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        TabPermission::create([
            "permission" => TabPermission::CLIENT_BILLING_CONFIG
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tap_permissions', function (Blueprint $table) {
            //
        });
    }
}
