<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminAddService extends Singleton
{
    public function mount(Component $component)
    {
        $component->fill([
            "styles" => Admin::styles()
        ]);
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->validate([
                'icon' => 'image|max:10240', // 1MB Max
            ]);
            $component->validate();
            $admin = Admin::create($this->mapper($component));
            $admin->buildOneImageFromFile("icon", $component->icon);
            $user = User::create(array_merge($this->mapper($component), [
                "password" => bcrypt($component->password),
                "type" => User::TYPE_ADMIN
            ]));

            $admin->update([
                "user_id" => $user->id
            ]);

            $component->redirectRoute("administrar.v1.usuarios.admin.detalles", ["admin" => $admin->id]);
        });
    }

    private function mapper($component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "address" => $component->address,
            "nit" => $component->nit,
            "identification" => $component->identification,
            "type" => User::TYPE_ADMIN,
            "css_style" => $component->style

        ];
    }

    public function setStyle()
    {
    }
}
