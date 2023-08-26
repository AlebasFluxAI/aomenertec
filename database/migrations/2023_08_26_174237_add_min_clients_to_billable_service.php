<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinClientsToBillableService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_services', function (Blueprint $table) {
            $table->integer("min_clients")->default(0);
            $table->double("min_client_value")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_services', function (Blueprint $table) {
            $table->dropColumn("min_clients");
            $table->dropColumn("min_client_value");
        });
    }
}
