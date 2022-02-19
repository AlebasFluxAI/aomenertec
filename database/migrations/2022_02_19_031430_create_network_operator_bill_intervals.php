<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkOperatorBillIntervals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_operator_bill_intervals', function (Blueprint $table) {
            $table->id();
            $table->foreignId("network_operator_id")->constrained();
            $table->integer("interval")->default(3);
            $table->integer("day")->default(1);
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
        Schema::dropIfExists('network_operator_bill_intervals');
    }
}
