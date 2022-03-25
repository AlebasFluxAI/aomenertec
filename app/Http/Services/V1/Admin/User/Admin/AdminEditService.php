<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use Livewire\Component;

class AdminEditService extends Singleton
{
    public function mount(Component $component, Admin $model)
    {
        $component->fill([
            'model' => $model,
            'name' => $model->user->name,
            'last_name' => $model->user->last_name,
            'phone' => $model->user->phone,
            'email' => $model->user->email,
            'password' => $model->user->password,
            'identification' => $model->user->identification,
        ]);
    }


    public function submitForm(Component $component)
    {
        $component->model->fill($this->mapper($component));
        $component->model->update();
        $component->emitTo('livewire-toast', 'show', "Administrador {$component->model->name} creado exitosamente");

    }

    private function mapper(Component $component)
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
