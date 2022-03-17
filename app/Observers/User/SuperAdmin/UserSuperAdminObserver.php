<?php

namespace App\Observers\User\SuperAdmin;

use App\Models\V1\SuperAdmin;
use App\Models\V1\User;

class UserSuperAdminObserver
{
    /**
     * Handle the SuperAdmin "created" event.
     *
     * @param SuperAdmin $superAdmin
     * @return void
     */
    public function created(SuperAdmin $superAdmin)
    {
        User::create([
            "name" => $superAdmin->name,
            "last_name" => $superAdmin->last_name,
            "phone" => $superAdmin->phone,
            "identification" => $superAdmin->identification
        ]);
    }

    /**
     * Handle the SuperAdmin "updated" event.
     *
     * @param SuperAdmin $superAdmin
     * @return void
     */
    public function updated(SuperAdmin $superAdmin)
    {
        //
    }

    /**
     * Handle the SuperAdmin "deleted" event.
     *
     * @param SuperAdmin $superAdmin
     * @return void
     */
    public function deleted(SuperAdmin $superAdmin)
    {
        //
    }

    /**
     * Handle the SuperAdmin "restored" event.
     *
     * @param SuperAdmin $superAdmin
     * @return void
     */
    public function restored(SuperAdmin $superAdmin)
    {
        //
    }

    /**
     * Handle the SuperAdmin "force deleted" event.
     *
     * @param SuperAdmin $superAdmin
     * @return void
     */
    public function forceDeleted(SuperAdmin $superAdmin)
    {
        //
    }
}
