<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Services\Singleton;
use Livewire\Component;

class NetworkOperatorIndexService extends Singleton
{


    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.operadores.editar", ["networkOperator" => $modelId]);

    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.operadores.detalles", ["networkOperator" => $modelId]);
    }

}
