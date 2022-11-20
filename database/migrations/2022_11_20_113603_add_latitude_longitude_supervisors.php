<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatitudeLongitudeSupervisors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervisors', function (Blueprint $table) {
            $table->double("latitude")->nullable();
            $table->double("longitude")->nullable();
            $table->text("here_maps")->nullable();
            $table->string("postal_code")->nullable();
            $table->string("address")->nullable();
            $table->string("country")->nullable();
            $table->string("state")->nullable();
            $table->string("city")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropColumn("latitude");
            $table->dropColumn("longitude");
            $table->dropColumn("here_maps");
            $table->dropColumn("postal_code");
            $table->dropColumn("address");
            $table->dropColumn("country");
            $table->dropColumn("state");
            $table->dropColumn("city");

        });
    }
}
