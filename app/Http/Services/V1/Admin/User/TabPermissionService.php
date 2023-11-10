<?php

namespace App\Http\Services\V1\Admin\User;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\TabPermission;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class TabPermissionService extends Singleton
{

    public function mount(Component $component)
    {
        $user_type = Request::input("user_type");
        $id = Request::input("id");
        $component->fill([
            "tab_permissions" => TabPermission::get(),
            "model_class" => $user_type,
            "model" => ($user_type)::find($id),
        ]);

    }

    public function enabled(Component $component, $tabPermissionId)
    {
        return ($component->model->tabPermissions()->whereTabPermissionId($tabPermissionId)->exists());
    }

    public function blinkTabPermission(Component $component, $permissionId)
    {
        $component->model->addTabPermission($permissionId);
        $component->model->refresh();
    }

    public function clients(Component $component, $tabPermissionId)
    {
        return (Client::whereIn("id", $component->model
            ->tabPermissions()
            ->whereTabPermissionId($tabPermissionId)
            ->whereConditionableType(Client::class)
            ->pluck("conditionable_id"))->pluck("identification"));
    }
}
