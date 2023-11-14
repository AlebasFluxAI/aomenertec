<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin;

use App\Http\Services\Singleton;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SuperAdminAddService extends Singleton
{
    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->validate();
            $super_admin = new SuperAdmin($component->model);
            $super_admin->save();
            $user = User::create(array_merge($component->model, [
                "type" => User::TYPE_SUPER_ADMIN
            ]));
            $super_admin->update([
                "user_id" => $user->id
            ]);
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$super_admin->name} creado"]);
            $component->redirectRoute("administrar.v1.usuarios.superadmin.detalles", ["superAdmin" => $super_admin->id]);

        });
    }

    public function updated(Component $component, $propertyName)
    {
        $component->validateOnly($propertyName);
    }
}
