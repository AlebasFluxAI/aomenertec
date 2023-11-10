<?php

namespace App\Observers\ActionBy;

use Illuminate\Support\Facades\Auth;

class ActionByObserve
{
    /**
     * Handle the "created" event.
     *
     * @param mixed $models
     */
    public function creating($models)
    {
        if (($user = Auth::user())) {
            $models->created_by = $user->id;
        }
    }

    /**
     * Handle the "updated" event.
     *
     * @param mixed $models
     */
    public function updating($models)
    {
        if (($user = Auth::user())) {
            $models->updated_by = $user->id;
        }
    }

    /**
     * Handle the "deleted" event.
     *
     * @param mixed $models
     */
    public function deleting($models)
    {
        if (($user = Auth::user())) {
            $models->deleted_by = $user->id;
        }
    }
}
