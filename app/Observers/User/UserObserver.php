<?php

namespace App\Observers\User;

use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;

class UserObserver
{
    public function created(User $user)
    {
        switch ($user->type) {
            case User::TYPE_NETWORK_OPERATOR:
                $user->assignRole(NetworkOperator::getRole());
                break;
            case User::TYPE_TECHNICIAN:
                $user->assignRole(Technician::getRole());
                break;
            case User::TYPE_ADMIN:
                $user->assignRole(Admin::getRole());
                break;
            case User::TYPE_SUPERVISOR:
                $user->assignRole(Supervisor::getRole());
                break;
            case User::TYPE_SUPER_ADMIN:
                $user->assignRole(SuperAdmin::getRole());
                break;
            case User::TYPE_SELLER:
                $user->assignRole(Seller::getRole());
                break;
            default:
                $user->assignRole(Admin::getRole());
        }


    }
}
