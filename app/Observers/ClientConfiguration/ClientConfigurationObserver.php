<?php

namespace App\Observers\ClientConfiguration;

use App\Models\V1\ClientConfiguration;

class ClientConfigurationObserver
{
    public function created(ClientConfiguration $clientConfiguration)
    {
        $clientConfiguration->setRemoteConfiguration();
    }

    public function updated(ClientConfiguration $clientConfiguration)
    {
        $clientConfiguration->setRemoteConfiguration();
    }
}
