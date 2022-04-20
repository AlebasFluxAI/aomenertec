<?php

namespace App\Http\Services\V1\Admin\User\Technician;

use App\Http\Services\Singleton;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use Illuminate\Support\Facades\Auth;
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

    public function getData(Component $component)
    {
        $user = Auth::user();

        if ($networkOperator = $user->networkOperator) {
            if ($component->filter) {
                return $networkOperator->technicians()->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
            }
            return $networkOperator->technicians()->paginate(15);
        }

        if ($admin = $user->admin) {
            if ($component->filter) {
                return Technician::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
            }
            return Technician::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))->paginate(15);
        }


        if ($component->filter) {
            return Technician::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
        }
        return Technician::paginate(15);
    }
}
