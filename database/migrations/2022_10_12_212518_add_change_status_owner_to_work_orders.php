<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangeStatusOwnerToWorkOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('open_by')->nullable();
            $table->foreign('open_by')->on('users')->references('id');
            $table->unsignedBigInteger('in_progress_by')->nullable();
            $table->foreign('in_progress_by')->on('users')->references('id');
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->foreign('closed_by')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            //
        });
    }
}
