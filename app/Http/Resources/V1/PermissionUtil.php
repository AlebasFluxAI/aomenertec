<?php

namespace App\Http\Resources\V1;

use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;

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

    public static function getAddTechnicianToClientRoles()
    {
        return Admin::getRole() . "|" . NetworkOperator::getRole();
    }
}
