<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkOperatorTimelyPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_operator_timely_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("network_operator_id")->constrained();
            $table->integer("days_to_disconnection")->default(5);
            $table->integer("days_to_payment")->default(5);
            $table->double("reconnection_cost")->default(0.0);
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
        Schema::dropIfExists('network_operator_timely_payments');
    }
}
