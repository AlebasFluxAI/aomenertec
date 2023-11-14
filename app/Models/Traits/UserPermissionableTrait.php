<?php

namespace App\Models\Traits;


use App\Models\TabPermissionUser;
use App\Models\V1\TabPermission;

trait UserPermissionableTrait
{
    public function addTabPermissionPlusConditional($tabPermissionId, $conditionalModel)
    {
        $this->tabPermissions()->create([
            "tab_permission_id" => $tabPermissionId,
            "conditionable_type" => $conditionalModel::class,
            "conditionable_id" => $conditionalModel->id,
        ]);
    }

    public function tabPermissions()
    {
        return $this->morphMany(TabPermissionUser::class, "permissionable");
    }

    public function removeTabPermissionPlusConditional($tabPermissionId, $conditionalModel)
    {
        $this->tabPermissions()->where([
            "tab_permission_id" => $tabPermissionId,
            "conditionable_type" => $conditionalModel::class,
            "conditionable_id" => $conditionalModel->id,
        ])->delete();
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

    public function tabPermissionExist($permissionName)
    {
        $tabPermission = TabPermission::wherePermission($permissionName)->first()->id;
        return $this->tabPermissions()->whereTabPermissionId($tabPermission)->exists();
    }

    public function tabPermissionConditionableExist($permissionName, $model)
    {

        $tabPermission = TabPermission::wherePermission($permissionName)->first()->id;

        return $this->tabPermissions()
            ->whereConditionableId($model->id)
            ->whereConditionableType($model::class)
            ->whereTabPermissionId($tabPermission)->exists();
    }

}
