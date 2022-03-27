<?php

namespace App\Observers\User\Admin;

use App\Models\V1\Admin;
use App\Models\V1\User;

class UserAdminObserver
{
    /**
     * Handle the SuperAdmin "created" event.
     *
     * @param Admin $admin
     * @return void
     */
    public function creating(Admin $admin)
    {

        $user = $admin->user;
        if (!$user) {
            return;
        }

        $admin->email = $user->email;
        $admin->name = $user->name;
        $admin->last_name = $user->last_name;
        $admin->phone = $user->phone;
        $admin->identification = $user->identification;

    }


    /**
     * Handle the SuperAdmin "updated" event.
     *
     * @param Admin $admin
     * @return void
     */
    public function updated(Admin $admin)
    {
        $user = $admin->user;
        if (!$user) {
            return;
        }

        $user->update([
            "name" => $admin->name,
            "last_name" => $admin->last_name,
            "email" => $admin->email,
            "phone" => $admin->phone,
            "identification" => $admin->identification,
        ]);

    }

    /**
     * Handle the SuperAdmin "restored" event.
     *
     * @param Admin $admin
     * @return void
     */
    public function restored(Admin $admin)
    {
        //
    }

    /**
     * Handle the SuperAdmin "force deleted" event.
     *
     * @param Admin $admin
     * @return void
     */
    public function forceDeleted(Admin $admin)
    {
        //
    }
}
