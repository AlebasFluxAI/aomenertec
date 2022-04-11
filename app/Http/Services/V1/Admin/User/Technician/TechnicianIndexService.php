<?php

namespace App\Http\Services\V1\Admin\User\Technician;

use App\Http\Services\Singleton;
use Livewire\Component;

class TechnicianIndexService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.tecnicos.editar", ["technician" => $modelId]);
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.tecnicos.detalles", ["technician" => $modelId]);
    }

    public function addClients(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.tecnicos.agregar_clientes", ["technician" => $modelId]);

    }
}
