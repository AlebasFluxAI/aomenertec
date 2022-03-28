<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Livewire\Component;

class SuperAdminAddService extends Singleton
{
    public function submitForm(Component $component)
    {
        $component->validate();


        $superAdmin = SuperAdmin::create($this->mapper($component));
        $user = User::create(array_merge($this->mapper($component), [
            "password" => bcrypt($component->password),
            "type" => User::TYPE_SUPER_ADMIN
        ]));
        $superAdmin->update([
            "user_id" => $user->id
        ]);

        $component->redirectRoute("administrar.v1.usuarios.superadmin.detalles", ["superAdmin" => $superAdmin->id]);
    }

    private function mapper($component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "identification" => $component->identification
        ];
    }
}
