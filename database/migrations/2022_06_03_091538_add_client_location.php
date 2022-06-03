<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\V1\Client;
use App\Models\V1\ClientAddress;

class AddClientLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Client::get() as $client) {
            if ($client->addresses->count() > 0) {
                continue;
            }
            if (!$client->latitude or !$client->longitude) {
                continue;
            }

            $client->addresses()->create([
                "latitude" => $client->latitude,
                "longitude" => $client->longitude,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
