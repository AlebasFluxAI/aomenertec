<?php

namespace App\Observers\User\Technician;

use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;

class UserTechnicianObserver
{
    public function creating(Technician $technician)
    {
        $user = $technician->user;
        if (!$user) {
            return;
        }

        $technician->name = $user->name;
        $technician->email = $user->email;
        $technician->last_name = $user->last_name;
        $technician->phone = $user->phone;
        $technician->identification = $user->identification;
    }

    public function updated(Technician $technician)
    {
        $user = $technician->user;
        if (!$user) {
            return;
        }

        $user->update([
            "name" => $technician->name,
            "last_name" => $technician->last_name,
            "email" => $technician->email,
            "phone" => $technician->phone,
            "identification" => $technician->identification,
        ]);
    }
}
