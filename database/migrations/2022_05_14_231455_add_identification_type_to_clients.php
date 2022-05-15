<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\V1\Client;

class AddIdentificationTypeToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->enum("person_type", [
                Client::PERSON_TYPE_NATURAL,
                Client::PERSON_TYPE_JURIDICAL,
            ]);
            $table->enum("identification_type", [
                Client::IDENTIFICATION_TYPE_PP,
                Client::IDENTIFICATION_TYPE_PEP,
                Client::IDENTIFICATION_TYPE_CC,
                Client::IDENTIFICATION_TYPE_NIT,
                Client::IDENTIFICATION_TYPE_CE,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
