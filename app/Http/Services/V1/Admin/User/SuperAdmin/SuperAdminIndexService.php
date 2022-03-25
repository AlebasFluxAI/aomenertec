<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin;

use App\Http\Services\Singleton;
use Livewire\Component;

class SuperAdminIndexService extends Singleton
{


    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.superadmin.editar", ["superAdmin" => $modelId]);

    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.superadmin.detalles", ["superAdmin" => $modelId]);
    }

}
