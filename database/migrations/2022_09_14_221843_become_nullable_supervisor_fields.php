<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BecomeNullableSupervisorFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropUnique("supervisors_email_unique");
            $table->dropUnique("supervisors_identification_unique");
            $table->dropUnique("supervisors_phone_unique");
        });

        Schema::table('supervisors', function (Blueprint $table) {
            $table->string('identification')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('email')->nullable()->change();
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
