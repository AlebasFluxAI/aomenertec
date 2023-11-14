<?php

use App\Models\V1\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStratificationClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->enum("vaupes_stratification_type", [
                Client::RESIDENCE_1_41R,
                Client::RESIDENCE_2_42R,
                Client::RESIDENCE_3_43R,
                Client::OFFICIAL_1_410,
                Client::OFFICIAL_2_420,
                Client::COMMERCIAL_1_41C,
                Client::COMMERCIAL_2_42C,
                Client::COMMERCIAL_3_43C,
                Client::SUSPENDED_R1_R2,
            ])->default(Client::RESIDENCE_1_41R,);
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
