<?php

namespace App\Observers\User\SuperAdmin;

use App\Models\V1\SuperAdmin;

class UserSuperAdminObserver
{
    public function creating(SuperAdmin $superAdmin)
    {
        $user = $superAdmin->user;
        if (!$user) {
            return;
        }
        $superAdmin->email = $user->email;
        $superAdmin->name = $user->name;
        $superAdmin->last_name = $user->last_name;
        $superAdmin->phone = $user->phone;
        $superAdmin->identification = $user->identification;
    }


    public function updated(SuperAdmin $superAdmin)
    {
        $user = $superAdmin->user;
        if (!$user) {
            return;
        }

        $user->update([
            "name" => $superAdmin->name,
            "last_name" => $superAdmin->last_name,
            "email" => $superAdmin->email,
            "phone" => $superAdmin->phone,
            "identification" => $superAdmin->identification,
        ]);
    }

    public function deleted(SuperAdmin $superAdmin)
    {
    }
}
