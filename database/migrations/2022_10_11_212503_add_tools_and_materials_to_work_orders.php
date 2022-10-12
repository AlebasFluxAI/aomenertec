<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToolsAndMaterialsToWorkOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->text("tools")->nullable();
            $table->text("materials")->nullable();
            $table->text("days")->nullable();
            $table->text("hours")->nullable();
            $table->text("minutes")->nullable();
            $table->timestamp("open_at")->nullable();
            $table->timestamp("in_progress_at")->nullable();
            $table->timestamp("closed_at")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn("tools");
            $table->dropColumn("materials");
            $table->dropColumn("days");
            $table->dropColumn("hours");
            $table->dropColumn("minutes");
            $table->dropColumn("open_at");
            $table->dropColumn("in_progress_at");
            $table->dropColumn("closed_at");
        });
    }
}
