<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePqrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqrs', function (Blueprint $table) {
            $table->id();
            $table->string("detail");
            $table->foreignId('pqr_state_id')->default(1)->constrained();
            $table->foreignId('pqr_type_id')->constrained();
            $table->foreignId('network_operator_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId("support_id")->constrained();
            $table->text("solution");
            $table->timestamp("solution_date");
            $table->boolean("accepted_solution");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pqrs');
    }
}
