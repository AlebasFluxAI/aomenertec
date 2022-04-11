<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use Livewire\Component;

class AdminDetailsService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }

    public function edit(Component $component)
    {
        $component->redirectRoute("administrar.v1.usuarios.admin.editar", ["admin" => $component->model->id]);
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.admin.detalles", ["admin" => $modelId]);
    }
}
