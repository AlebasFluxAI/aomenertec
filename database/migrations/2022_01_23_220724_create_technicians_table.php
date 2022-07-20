<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechniciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technicians', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->boolean('enabled')->default(true);
            $table->string('identification')->unique();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string("address")->nullable();
            $table->string("address_details")->nullable();
            $table->double("latitude")->nullable();
            $table->double("longitude")->nullable();
            $table->json("here_maps")->nullable();
            $table->string("postal_code")->nullable();
            $table->string("country")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('network_operator_id')->constrained();
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
        Schema::dropIfExists('technicians');
    }
}
