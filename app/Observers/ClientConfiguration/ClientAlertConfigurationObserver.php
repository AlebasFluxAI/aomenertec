<?php

namespace App\Observers\ClientConfiguration;

use App\Models\V1\ClientAlertConfiguration;
use App\Notifications\Alert\AlertNotification;

class ClientAlertConfigurationObserver
{
    public function updated(ClientAlertConfiguration $clientAlertConfiguration)
    {

        //$clientAlertConfiguration->setRemoteConfiguration();
    }
}
