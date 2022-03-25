<?php

namespace App\Http\Services\V1\Admin\User\Supervisor;

use App\Http\Services\Singleton;
use Livewire\Component;

class SupervisorIndexService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.supervisores.editar", ["supervisor" => $modelId]);

    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.supervisores.detalles", ["supervisor" => $modelId]);
    }

}
