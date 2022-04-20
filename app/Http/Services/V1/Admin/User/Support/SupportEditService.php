<?php

namespace App\Http\Services\V1\Admin\User\Support;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\Support;
use Livewire\Component;

class SupportEditService extends Singleton
{
    public function mount(Component $component, Support $model)
    {
        $component->fill([
            'model' => $model,
            'name' => $model->name,
            'last_name' => $model->last_name,
            'phone' => $model->phone,
            'email' => $model->email,
            'identification' => $model->identification,
            "clients" => [],
            "client_picked" => false
        ]);
    }


    public function submitForm(Component $component)
    {
        $component->validate();
        $component->model->fill($this->mapper($component));
        $component->model->update();
        $component->redirectRoute("administrar.v1.usuarios.soporte.detalles", ["support" => $component->model->id]);
    }

    private function mapper(Component $component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "identification" => $component->identification,
        ];
    }
}
