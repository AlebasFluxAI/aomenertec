<?php

use App\Models\V1\ClientAddress;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_addresses', function (Blueprint $table) {
            $table->id();
            $table->double("latitude")->default(0.0);
            $table->double("longitude")->default(0.0);
            $table->string("address")->nullable();
            $table->string("country")->nullable();
            $table->string("details")->nullable();
            $table->string("city")->nullable();
            $table->enum("status", [ClientAddress::STATUS_DISABLED, ClientAddress::STATUS_ENABLED])->default(ClientAddress::STATUS_ENABLED);
            $table->foreignId("client_id")->constrained();
            $table->json("here_maps")->nullable();
            $table->string("state")->nullable();
            $table->string("postal_code")->nullable();
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
        Schema::dropIfExists('client_addresses');
    }
}
