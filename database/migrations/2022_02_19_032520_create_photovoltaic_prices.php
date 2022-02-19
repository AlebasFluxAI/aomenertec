<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotovoltaicPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photovoltaic_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId("network_operator_id")->constrained();
            $table->foreignId("stratum_id")->constrained();
            $table->double("subsidy")->default(0.0);
            $table->double("price")->default(0.0);
            $table->double("credit")->default(0.0);
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
        Schema::dropIfExists('photovoltaic_prices');
    }
}
