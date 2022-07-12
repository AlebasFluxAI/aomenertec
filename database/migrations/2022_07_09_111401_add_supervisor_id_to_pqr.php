<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupervisorIdToPqr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqrs', function (Blueprint $table) {
            $table->foreignId("supervisor_id")->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pqrs', function (Blueprint $table) {
            $table->dropColumn("supervisor_id");
        });
    }
}
