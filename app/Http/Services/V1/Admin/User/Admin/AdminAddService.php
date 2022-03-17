<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\User;
use Livewire\Component;

class AdminAddService extends Singleton
{

    public function submitForm(Component $component)
    {

        $user = User::create($this->mapper($component));
        $user->admin()->create();
        $user->assignRole(Admin::getRole());
    }

    private function mapper($component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "password" => bcrypt($component->password),
            "identification" => $component->identification
        ];
    }
}
