<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultRateToPricesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zni_level_fees', function (Blueprint $table) {
            $table->integer('default_rate')->default(0);
        });
        Schema::table('sin_level_fees', function (Blueprint $table) {
            $table->integer('default_rate')->default(0);
        });
        Schema::table('photovoltaic_prices', function (Blueprint $table) {
            $table->integer('default_rate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zni_level_fees', function (Blueprint $table) {
            $table->dropColumn('default_rate');
        });
        Schema::table('sin_level_fees', function (Blueprint $table) {
            $table->dropColumn('default_rate');
        });
        Schema::table('photovoltaic_prices', function (Blueprint $table) {
            $table->dropColumn('default_rate');
        });
    }
}
