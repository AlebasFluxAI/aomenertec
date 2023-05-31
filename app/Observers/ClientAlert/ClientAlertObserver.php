<?php

namespace App\Observers\ClientAlert;

use App\Jobs\V1\Enertec\AlertNotificationJob;
use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\ClientDigitalOutputAlertConfiguration;
use App\Models\V1\EquipmentType;
use App\Models\V1\User;
use App\Notifications\Alert\AlertControlNotification;
use App\Notifications\Alert\AlertNotification;
use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;

class ClientAlertObserver
{
    /**
     * Handle the ClientAlert "created" event.
     *
     * @param \App\Models\V1\ClientAlert $clientAlert
     * @return void
     */
    public function created(ClientAlert $clientAlert)
    {
        dispatch(new AlertNotificationJob($clientAlert))->onQueue('spot2');
    }

    /**
     * Handle the ClientAlert "updated" event.
     *
     * @param \App\Models\ClientAlert $clientAlert
     * @return void
     */
    public function updated(ClientAlert $clientAlert)
    {
        //
    }

    /**
     * Handle the ClientAlert "deleted" event.
     *
     * @param \App\Models\ClientAlert $clientAlert
     * @return void
     */
    public function deleted(ClientAlert $clientAlert)
    {
        //
    }

    /**
     * Handle the ClientAlert "restored" event.
     *
     * @param \App\Models\ClientAlert $clientAlert
     * @return void
     */
    public function restored(ClientAlert $clientAlert)
    {
        //
    }

    /**
     * Handle the ClientAlert "force deleted" event.
     *
     * @param \App\Models\ClientAlert $clientAlert
     * @return void
     */
    public function forceDeleted(ClientAlert $clientAlert)
    {
        //
    }
}
