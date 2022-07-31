<?php

namespace App\Http\Services\V1\Admin\User\Support;

use App\Http\Services\Singleton;
use App\Models\V1\Support;
use Illuminate\Support\Facades\Auth;
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

    public function getData(Component $component)
    {
        if ($component->filter) {
            return Support::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
        }
        return Support::paginate(15);
    }

    public function supportPqrDisabled(Component $component, $support)
    {
        return Support::whereId($support)->wherePqrAvailable(true)->exists();
    }

    public function enablePqrSupport(Component $component, $support)
    {
        Support::find($support)->blinkPqrAvailability();
    }
}
