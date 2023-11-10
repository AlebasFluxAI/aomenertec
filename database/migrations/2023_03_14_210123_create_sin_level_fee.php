<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinLevelFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sin_level_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId("voltage_level_id")->constrained();
            $table->foreignId("network_operator_id")->constrained();
            $table->double("generation")->default(0.0);
            $table->double("transmission")->default(0.0);
            $table->double("distribution")->default(0.0);
            $table->double("commercialization")->default(0.0);
            $table->double("lost")->default(0.0);
            $table->double("restriction")->default(0.0);
            $table->double("unit_cost")->default(0.0);
            $table->double("total_fee")->default(0.0);
            $table->double("optional_fee")->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('sin_level_fees');
    }
}
