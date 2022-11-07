<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin;

use App\Http\Services\Singleton;
use App\Models\V1\SuperAdmin;
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


    public function getData(Component $component)
    {
        if ($component->filter) {
            return SuperAdmin::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }
        return SuperAdmin::pagination();
    }
}
