<?php

namespace App\Models\Traits;

trait PermissionTrait
{
    public function getPermissions(): array
    {
        return config("permissions." . self::class);
    }
}
