<?php

use App\Models\V1\Client;
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
            $table->enum("person_type", [
                Client::PERSON_TYPE_JURIDICAL,
                Client::PERSON_TYPE_NATURAL
            ])->default(Client::PERSON_TYPE_NATURAL);
            $table->enum("identification_type", [
                Client::IDENTIFICATION_TYPE_CC,
                Client::IDENTIFICATION_TYPE_CE,
                Client::IDENTIFICATION_TYPE_PEP,
                Client::IDENTIFICATION_TYPE_PP,
                Client::IDENTIFICATION_TYPE_NIT,
            ])->default(Client::IDENTIFICATION_TYPE_CC);
            $table->string("name");
            $table->string("last_name")->nullable();
            $table->string("email")->nullable()->unique();
            $table->string("phone")->nullable();
            $table->boolean("contribution")->default(false);
            $table->boolean("public_lighting_tax")->default(false);
            $table->boolean("active_client")->default(true);
            $table->foreignId("network_operator_id")->constrained();
            $table->foreignId("client_type_id")->nullable()->constrained();
            $table->foreignId("subsistence_consumption_id")->nullable()->default(1)->constrained();
            $table->foreignId("voltage_level_id")->nullable()->default(1)->constrained();
            $table->foreignId("stratum_id")->nullable()->constrained();
            $table->enum("network_topology", [Client::MONOPHASIC, Client::BIPHASIC, Client::TRIPHASIC])->default(Client::MONOPHASIC);
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
        Schema::dropIfExists('clients');
    }
}
