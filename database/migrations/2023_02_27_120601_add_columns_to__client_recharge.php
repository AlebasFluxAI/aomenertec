<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToClientRecharge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_recharges', function (Blueprint $table) {
            $table->integer("consecutive");
            $table->string("recharge_code");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_recharges', function (Blueprint $table) {
            $table->dropColumn("consecutive");
            $table->dropColumn("recharge_code");

        });
    }
}
