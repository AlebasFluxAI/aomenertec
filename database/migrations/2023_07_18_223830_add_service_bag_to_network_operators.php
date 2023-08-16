<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceBagToNetworkOperators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('network_operators', function (Blueprint $table) {
            $table->integer("pqr_initial_bag")->default(0);
            $table->integer("billing_day")->default(1);
            $table->double("work_order_initial_bag")->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('network_operators', function (Blueprint $table) {
            $table->dropColumn("pqr_initial_bag");
            $table->dropColumn("billing_day");
            $table->dropColumn("work_order_initial_bag");
        });
    }
}
