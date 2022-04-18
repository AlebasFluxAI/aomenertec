<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
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

    public function getData()
    {
        $user = Auth::user();
        $admin = $user->admin;
        if ($admin) {
            return $admin->networkOperators()->paginate(15);
        }
        return NetworkOperator::paginate(15);
    }

    public function deleteNetworkOperator($networkOperatorId)
    {

        NetworkOperator::whereId($networkOperatorId)->delete();

    }

    public function conditionalDelete($networkOperatorId)
    {

        return Client::whereNetworkOperatorId($networkOperatorId)->exists();
    }
}
