<?php

namespace App\Observers;

use App\Models\V1\ClientAddress;

class ClientAddressObserver
{
    public function creating(ClientAddress $clientAddress)
    {
        $clientAddress->setHereMapJson();
    }
}
