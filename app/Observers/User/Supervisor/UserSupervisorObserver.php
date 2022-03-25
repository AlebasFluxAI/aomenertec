<?php

namespace App\Observers\User\Supervisor;

use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;

class UserSupervisorObserver
{
    public function creating(Supervisor $supervisor)
    {
        $user = $supervisor->user;
        if (!$user) {
            return;
        }
        $supervisor->email = $user->email;
        $supervisor->name = $user->name;
        $supervisor->last_name = $user->last_name;
        $supervisor->phone = $user->phone;
        $supervisor->identification = $user->identification;

    }


    public function updated(Supervisor $supervisor)
    {
        $user = $supervisor->user;
        if (!$user) {
            return;
        }

        $user->update([
            "name" => $supervisor->name,
            "last_name" => $supervisor->last_name,
            "email" => $supervisor->email,
            "phone" => $supervisor->phone,
            "identification" => $supervisor->identification,
        ]);
    }
}
