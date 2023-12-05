<?php

namespace App\Observers\Client;

use App\Models\V1\Client;
use Illuminate\Support\Str;

class ClientObserver
{
    public function creating(Client $client)
    {

        $client->identification = $client->identification ?? Str::uuid();
        $client->client_type_id = $client->client_type_id != 0 ? $client->client_type_id : 1;

    }
}
