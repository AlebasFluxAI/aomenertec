<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePqrRelationsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqrs', function (Blueprint $table) {
            $table->dropColumn("client_id");
            $table->dropColumn("network_operator_id");
            $table->dropColumn("pqr_type_id");
            $table->dropColumn("support_id");

        });

        Schema::table('pqrs', function (Blueprint $table) {
            $table->foreignId("client_id")->nullable()->constrained();
            $table->foreignId("network_operator_id")->nullable()->constrained();
            $table->foreignId("pqr_type_id")->nullable()->constrained();
            $table->foreignId("support_id")->nullable()->constrained();

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
