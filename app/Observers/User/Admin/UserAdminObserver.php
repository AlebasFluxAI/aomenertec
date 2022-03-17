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
    public function created(Admin $admin)
    {
        $user = User::create([
            "name" => $admin->name,
            "last_name" => $admin->last_name,
            "phone" => $admin->phone,
            "identification" => $admin->identification
        ]);

        $user->assignRole($admin->getRole());
    }

    /**
     * Handle the SuperAdmin "updated" event.
     *
     * @param Admin $admin
     * @return void
     */
    public function updated(Admin $admin)
    {
        //
    }

    /**
     * Handle the SuperAdmin "deleted" event.
     *
     * @param Admin $admin
     * @return void
     */
    public function deleted(Admin $admin)
    {
        //
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
