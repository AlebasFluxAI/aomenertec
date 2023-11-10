<?php

namespace App\Http\Services\V1\Admin\User;

use App\Http\Services\Singleton;
use App\Models\V1\User;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class SelectRoleUserService extends Singleton
{

    public function mount(Component $component)
    {
        $component->fill([
            "roles" => User::getUserRoles()
        ]);
    }

    public function selectRole(Component $component, $role)
    {
        Request::session()->put(User::SESSION_ROLE_SELECTED, $role);
        $component->redirectRoute("administrar.v1.perfil");

    }
}
