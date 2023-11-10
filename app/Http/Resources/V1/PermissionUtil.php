<?php

namespace App\Http\Resources\V1;

use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;

class PermissionUtil
{
    public static function getNetworkOperatorEquipmentTypeRoles()
    {
        return Admin::getRole();
    }

    public static function getTechnicianEquipmentTypeRoles()
    {
        return NetworkOperator::getRole();
    }
}
