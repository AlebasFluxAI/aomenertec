<?php

namespace App\Http\Services\V1\Admin\User\Supervisor;

use App\Http\Services\Singleton;
use App\Models\V1\Seller;
use App\Models\V1\Supervisor;
use Illuminate\Support\Facades\Auth;
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

    public function addClients(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.supervisores.agregar_clientes", ["supervisor" => $modelId]);

    }


    public function getData(Component $component)
    {
        $user = Auth::user();
        if ($networkOperator = $user->networkOperator) {
            if ($component->filter) {
                return $networkOperator->supervisors()->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
            }
            return $networkOperator->supervisors()->paginate(15);
        }

        if ($admin = $user->admin) {
            if ($component->filter) {
                return Supervisor::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
            }
            return Supervisor::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))->paginate(15);
        }


        if ($component->filter) {
            return Supervisor::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
        }
        return Supervisor::paginate(15);
    }
}
