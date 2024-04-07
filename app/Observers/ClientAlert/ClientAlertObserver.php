<?php

namespace App\Observers\ClientAlert;

use App\Jobs\V1\Enertec\AlertNotificationJob;
use App\Models\V1\ClientAlert;

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
        dispatch(new AlertNotificationJob($clientAlert))->onQueue('default');
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
