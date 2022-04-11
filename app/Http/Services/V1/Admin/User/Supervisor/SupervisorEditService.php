<?php

namespace App\Http\Services\V1\Admin\User\Supervisor;

use App\Http\Services\Singleton;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class SupervisorEditService extends Singleton
{
    public function mount(Component $component, SuperAdmin $model)
    {
        $component->fill([
            'model' => $model,
            'name' => $model->name,
            'last_name' => $model->last_name,
            'phone' => $model->phone,
            'email' => $model->email,
            'password' => $model->password,
            'identification' => $model->identification,
        ]);
    }

    public function submitForm(Component $component)
    {
        $component->model->fill($this->mapper($component));
        $component->model->update();
        $component->redirectRoute("administrar.v1.usuarios.supervisores.detalles", ["supervisor" => $component->model->id]);
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
