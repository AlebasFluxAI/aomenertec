<?php

use App\Models\V1\TabPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTabPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tab_permissions', function (Blueprint $table) {
            $table->id();
            $table->string("permission");
            $table->timestamps();
        });

        TabPermission::create([
            "permission" => TabPermission::CLIENT_CONFIG_CONNECTION
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_tab_permissions');
    }
}
