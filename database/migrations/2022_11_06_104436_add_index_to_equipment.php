<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToEquipment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->index("equipment_type_id");
            $table->index("admin_id");
            $table->index("network_operator_id");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropIndex("equipment_type_id");
            $table->dropIndex("admin_id");
            $table->dropIndex("network_operator_id");

        });
    }
}
