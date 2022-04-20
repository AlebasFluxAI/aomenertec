<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\V1\Client;

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
            $table->foreignId("department_id")->nullable()->constrained();
            $table->foreignId("municipality_id")->nullable()->constrained();
            $table->foreignId("location_id")->nullable()->constrained();
            $table->foreignId("client_type_id")->nullable()->constrained();
            $table->foreignId("subsistence_consumption_id")->nullable()->default(1)->constrained();
            $table->foreignId("voltage_level_id")->nullable()->default(1)->constrained();
            $table->foreignId("stratum_id")->nullable()->constrained();
            $table->enum("network_topology", [Client::MONOPHASIC, Client::BIPHASIC, Client::TRIPHASIC])->default(Client::MONOPHASIC);
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
