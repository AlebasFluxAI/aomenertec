<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string("address")->nullable();
            $table->double("latitude")->nullable();
            $table->json("here_maps")->nullable();
            $table->double("longitude")->nullable();
            $table->string("address_details")->nullable();
            $table->string("postal_code")->nullable();
            $table->string("country")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn("address");
            $table->dropColumn("latitude");
            $table->dropColumn("here_maps");
            $table->dropColumn("longitude");
            $table->dropColumn("address_details");
            $table->dropColumn("postal_code");
            $table->dropColumn("country");
            $table->dropColumn("city");
            $table->dropColumn("state");
        });
    }
}
