<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BecomeNullableBillingInformationFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_informations', function (Blueprint $table) {
            $table->string("address")->nullable()->change();
            $table->string("identification")->nullable()->change();
            $table->string("phone")->nullable()->change();
            $table->string("name")->nullable()->change();
            $table->string("identification_type")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
