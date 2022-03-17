<?php

namespace App\Observers\User\Supervisor;

use App\Models\V1\Supervisor;

class UserSupervisorObserver
{
    /**
     * Handle the Supervisor "created" event.
     *
     * @param  \App\Models\V1\Supervisor  $supervisor
     * @return void
     */
    public function created(Supervisor $supervisor)
    {
        //
    }

    /**
     * Handle the Supervisor "updated" event.
     *
     * @param  \App\Models\V1\Supervisor  $supervisor
     * @return void
     */
    public function updated(Supervisor $supervisor)
    {
        //
    }

    /**
     * Handle the Supervisor "deleted" event.
     *
     * @param  \App\Models\V1\Supervisor  $supervisor
     * @return void
     */
    public function deleted(Supervisor $supervisor)
    {
        //
    }

    /**
     * Handle the Supervisor "restored" event.
     *
     * @param  \App\Models\V1\Supervisor  $supervisor
     * @return void
     */
    public function restored(Supervisor $supervisor)
    {
        //
    }

    /**
     * Handle the Supervisor "force deleted" event.
     *
     * @param  \App\Models\V1\Supervisor  $supervisor
     * @return void
     */
    public function forceDeleted(Supervisor $supervisor)
    {
        //
    }
}
