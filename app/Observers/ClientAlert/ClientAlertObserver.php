<?php

namespace App\Observers\ClientAlert;

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
        $users = User::find([8, 2]);
        foreach ($users as $user){
            event(new UserNotificationEvent(NotificationTypes::NOTIFICATION_CREATED, $user->id));
            $user->notify(new AlertNotification());
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
