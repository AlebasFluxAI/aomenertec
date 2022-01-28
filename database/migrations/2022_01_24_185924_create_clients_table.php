<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("identification")->unique();
            $table->string("name");
            $table->string("email")->nullable()->unique();
            $table->string("phone")->nullable();
            $table->string("direction")->nullable();
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
            $table->boolean("contribution")->default(false);
            $table->boolean("public_lighting_tax")->default(false);
            $table->boolean("active_client")->default(true);
            $table->foreignId("network_operator_id")->constrained();
            $table->foreignId("department_id")->constrained();
            $table->foreignId("municipality_id")->constrained();
            $table->foreignId("location_id")->constrained();
            $table->foreignId("client_type_id")->constrained();
            $table->foreignId("subsistence_consumption_id")->default(1)->constrained();
            $table->foreignId("voltage_level_id")->default(1)->constrained();
            $table->foreignId("stratum_id")->constrained();
            $table->foreignId("network_topology_id")->constrained();
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
        Schema::dropIfExists('clients');
    }
}
