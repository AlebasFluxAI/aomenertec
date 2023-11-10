<?php

namespace App\Http\Resources\V1;

class PermissionsRouteWard
{
    public const CUSTOM_PERMISSION_WARD_MIDDLEWARE = "custom_permissions";

    public static function permissionWard($permission)
    {
        return self::CUSTOM_PERMISSION_WARD_MIDDLEWARE . ":" . $permission;
    }
}
