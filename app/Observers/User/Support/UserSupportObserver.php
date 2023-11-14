<?php

namespace App\Observers\User\Support;

use App\Models\V1\Support;

class UserSupportObserver
{
    public function creating(Support $support)
    {
        $user = $support->user;
        if (!$user) {
            return;
        }

        $support->name = $user->name;
        $support->email = $user->email;
        $support->last_name = $user->last_name;
        $support->phone = $user->phone;
        $support->identification = $user->identification;
    }

    public function updated(Support $support)
    {
        $user = $support->user;
        if (!$user) {
            return;
        }

        $user->update([
            "name" => $support->name,
            "last_name" => $support->last_name,
            "email" => $support->email,
            "phone" => $support->phone,
            "identification" => $support->identification,
        ]);
    }
}
