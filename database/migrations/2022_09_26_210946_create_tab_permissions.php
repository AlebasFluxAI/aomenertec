<?php

use App\Models\V1\Admin;
use App\Models\V1\TabPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tab_permission_admins', function (Blueprint $table) {
            $table->id();
            $table->string("tab_permission_id");
            $table->foreignId("admin_id")->nullable()->constrained();
            $table->boolean("enabled")->default(true);
            $table->timestamps();
        });


        $permission = TabPermission::wherePermission(TabPermission::CLIENT_CONFIG_CONNECTION)->first();
        foreach (Admin::get() as $admin) {
            $admin->tabPermissions()->create([
                "tab_permission_id" => $permission->id,
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
        Schema::dropIfExists('tab_permissions');
    }
}
