<?php

namespace App\Observers\ClientConfiguration;

use App\Models\V1\ClientAlertConfiguration;

class ClientAlertConfigurationObserver
{
    public function updated(ClientAlertConfiguration $clientAlertConfiguration)
    {

        //$clientAlertConfiguration->setRemoteConfiguration();
    }
}
