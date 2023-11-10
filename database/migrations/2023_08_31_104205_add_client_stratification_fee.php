<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientStratificationFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaupes_client_stratification_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId("network_operator_id")->constrained();
            $table->foreignId("client_type_id")->constrained();
            $table->double("residence_1_41r")->default(0.0);
            $table->double("residence_2_42r")->default(0.0);
            $table->double("residence_3_43r")->default(0.0);
            $table->double("official_1_410")->default(0.0);
            $table->double("official_2_420")->default(0.0);
            $table->double("commercial_1_41c")->default(0.0);
            $table->double("commercial_2_42c")->default(0.0);
            $table->double("commercial_3_43c")->default(0.0);
            $table->double("suspended_r1_r2")->default(0.0);
            $table->integer("month")->default(0);
            $table->integer("year")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaupes_client_stratification_fees');
    }
}
