<?php

namespace App\Observers\AuditoryStatus;

use App\Models\V1\Pqr;
use Illuminate\Support\Facades\Auth;

class AuditoryStatusObserver
{


    public function updating(Pqr $models)
    {
        if ($models->isDirty("status")) {
            $models->{"status_" . $models->status . "_at"} = now();
            if (($user = Auth::user())) {
                $models->{"status_" . $models->status . "_by"} = $user->id;
            }
        }
    }
}
