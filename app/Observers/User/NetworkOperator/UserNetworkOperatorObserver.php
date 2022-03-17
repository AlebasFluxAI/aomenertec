<?php

namespace App\Observers\User\NetworkOperator;

use App\Models\V1\NetworkOperator;

class UserNetworkOperatorObserver
{
    /**
     * Handle the NetworkOperator "created" event.
     *
     * @param  \App\Models\V1\NetworkOperator  $networkOperator
     * @return void
     */
    public function created(NetworkOperator $networkOperator)
    {
        //
    }

    /**
     * Handle the NetworkOperator "updated" event.
     *
     * @param  \App\Models\V1\NetworkOperator  $networkOperator
     * @return void
     */
    public function updated(NetworkOperator $networkOperator)
    {
        //
    }

    /**
     * Handle the NetworkOperator "deleted" event.
     *
     * @param  \App\Models\V1\NetworkOperator  $networkOperator
     * @return void
     */
    public function deleted(NetworkOperator $networkOperator)
    {
        //
    }

    /**
     * Handle the NetworkOperator "restored" event.
     *
     * @param  \App\Models\V1\NetworkOperator  $networkOperator
     * @return void
     */
    public function restored(NetworkOperator $networkOperator)
    {
        //
    }

    /**
     * Handle the NetworkOperator "force deleted" event.
     *
     * @param  \App\Models\V1\NetworkOperator  $networkOperator
     * @return void
     */
    public function forceDeleted(NetworkOperator $networkOperator)
    {
        //
    }
}
