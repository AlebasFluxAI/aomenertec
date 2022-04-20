<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use Livewire\Component;

class AdminIndexService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.admin.editar", ["admin" => $modelId]);
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.admin.detalles", ["admin" => $modelId]);
    }

    public function getData(Component $component)
    {
        if ($component->filter) {
            return Admin::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
        }
        return Admin::paginate(15);
    }

}
