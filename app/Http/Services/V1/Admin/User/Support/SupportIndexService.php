<?php

namespace App\Http\Services\V1\Admin\User\Support;

use App\Http\Services\Singleton;
use Livewire\Component;

class SupportIndexService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.soporte.editar", ["support" => $modelId]);
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.soporte.detalles", ["support" => $modelId]);
    }

    public function addClients(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.soporte.agregar_clientes", ["support" => $modelId]);

    }
}
