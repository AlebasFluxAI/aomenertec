<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Livewire\Component;

class AdminAddService extends Singleton
{

    public function submitForm(Component $component)
    {
        $component->validate();

        $admin = Admin::create($this->mapper($component));
        
        $user = User::create(array_merge($this->mapper($component), [
            "password" => bcrypt($component->password),
            "type" => User::TYPE_ADMIN
        ]));

        $admin->update([
            "user_id" => $user->id
        ]);

    }

    private function mapper($component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "identification" => $component->identification,
            "type" => User::TYPE_ADMIN
        ];
    }
}
