<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateToNetworkOperationFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zni_level_fees', function (Blueprint $table) {
            $table->integer("month")->default(0);
            $table->integer("year")->default(0);
        });

        Schema::table('zni_other_fees', function (Blueprint $table) {
            $table->integer("month")->default(0);
            $table->integer("year")->default(0);
        });

        Schema::table('sin_level_fees', function (Blueprint $table) {
            $table->integer("month")->default(0);
            $table->integer("year")->default(0);
        });

        Schema::table('sin_other_fees', function (Blueprint $table) {
            $table->integer("month")->default(0);
            $table->integer("year")->default(0);
        });

        Schema::table('photovoltaic_prices', function (Blueprint $table) {
            $table->integer("month")->default(0);
            $table->integer("year")->default(0);
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
            $table->dropColumn("month");
            $table->dropColumn("year");
        });

        Schema::table('zni_other_fees', function (Blueprint $table) {
            $table->dropColumn("month");
            $table->dropColumn("year");
        });

        Schema::table('sin_level_fees', function (Blueprint $table) {
            $table->dropColumn("month");
            $table->dropColumn("year");
        });

        Schema::table('sin_other_fees', function (Blueprint $table) {
            $table->dropColumn("month");
            $table->dropColumn("year");
        });

        Schema::table('photovoltaic_prices', function (Blueprint $table) {
            $table->dropColumn("month");
            $table->dropColumn("year");
        });
    }
}
