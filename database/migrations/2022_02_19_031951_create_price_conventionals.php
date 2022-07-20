<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceConventionals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conventional_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId("network_operator_id")->constrained();
            $table->foreignId("stratum_id")->constrained();
            $table->foreignId("voltage_level_id")->constrained();
            $table->double("generation")->default(0.0);
            $table->double("commercialization")->default(0.0);
            $table->double("loss")->default(0.0);
            $table->double("optional_rate")->default(0.0);
            $table->double("total")->default(0.0);
            $table->boolean("use_optional")->default(false);
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
        Schema::dropIfExists('price_conventionals');
    }
}
