<?php

namespace App\Observers\ClientAlert;

use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\User;
use App\Notifications\Alert\AlertNotification;
use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;

class ClientAlertObserver
{
    /**
     * Handle the ClientAlert "created" event.
     *
     * @param  \App\Models\V1\ClientAlert  $clientAlert
     * @return void
     */
    public function created(ClientAlert $clientAlert)
    {
        $client =$clientAlert->client;
        $technicians = $client->clientTechnician;
        $supervisors = $client->supervisors;
        $flag = true;
        foreach ($technicians as $user){
            event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
            $user->user->notify(new AlertNotification($clientAlert));
        }
        foreach ($supervisors as $user){
            if ($user->user->phone == $client->phone){
                $flag = false;
            }
            event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->user->id));
            $user->user->notify(new AlertNotification($clientAlert));
        }
        if ($flag){
            $client->notify(new AlertNotification($clientAlert));
        }

    }

    /**
     * Handle the ClientAlert "updated" event.
     *
     * @param  \App\Models\ClientAlert  $clientAlert
     * @return void
     */
    public function updated(ClientAlert $clientAlert)
    {
        //
    }

    /**
     * Handle the ClientAlert "deleted" event.
     *
     * @param  \App\Models\ClientAlert  $clientAlert
     * @return void
     */
    public function deleted(ClientAlert $clientAlert)
    {
        //
    }

    /**
     * Handle the ClientAlert "restored" event.
     *
     * @param  \App\Models\ClientAlert  $clientAlert
     * @return void
     */
    public function restored(ClientAlert $clientAlert)
    {
        //
    }

    /**
     * Handle the ClientAlert "force deleted" event.
     *
     * @param  \App\Models\ClientAlert  $clientAlert
     * @return void
     */
    public function forceDeleted(ClientAlert $clientAlert)
    {
        //
    }
}
