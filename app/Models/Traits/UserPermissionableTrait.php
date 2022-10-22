<?php

namespace App\Models\Traits;

use App\Http\Livewire\V1\Admin\User\TabPermission;
use App\Models\TabPermissionUser;
use App\Models\V1\Admin;
use App\Models\V1\Image;
use App\Models\V1\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

trait UserPermissionableTrait
{
    public function tabPermissions()
    {
        return $this->morphMany(TabPermissionUser::class, "permissionable");
    }

    public function addTabPermission($tabPermissionId)
    {
        if ($this->tabPermissions()->whereTabPermissionId($tabPermissionId)->exists()) {
            $this->tabPermissions()->whereTabPermissionId($tabPermissionId)->delete();
            return;
        }
        $this->tabPermissions()->create([
            "tab_permission_id" => $tabPermissionId
        ]);
    }

    public function tabPermissionsName()
    {
        $permissions = [];
        foreach ($this->tabPermissions as $permission) {
            array_push($permissions, $permission->tabPermission->permission);
        }
        return $permissions;
    }


}
