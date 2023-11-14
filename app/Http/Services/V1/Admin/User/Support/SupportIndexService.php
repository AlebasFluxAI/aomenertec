<?php

namespace App\Http\Services\V1\Admin\User\Support;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\V1\Support;
use Illuminate\Support\Facades\DB;
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

    public function delete(Component $component, $modelId)
    {
        DB::transaction(function () use ($modelId) {
            $support = Support::find($modelId);
            $support->delete();
        });

        ToastEvent::launchToast($component, "success", "Usuario eliminado");

    }

    public function getData(Component $component)
    {
        if ($component->filter) {
            return Support::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }
        return Support::pagination();
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
