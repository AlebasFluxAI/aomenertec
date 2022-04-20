<?php

namespace App\Http\Services\V1\Admin\User\Seller;

use App\Http\Services\Singleton;
use App\Models\V1\Seller;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SellerIndexService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.vendedores.editar", ["seller" => $modelId]);
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.vendedores.detalles", ["seller" => $modelId]);
    }

    public function addClients(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.vendedores.agregar_clientes", ["seller" => $modelId]);

    }

    public function getData(Component $component)
    {
        $user = Auth::user();
        if ($networkOperator = $user->networkOperator) {
            if ($component->filter) {
                return $networkOperator->sellers()->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
            }
            return $networkOperator->sellers()->paginate(15);
        }
        if ($component->filter) {
            return Seller::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
        }
        return Seller::paginate(15);
    }
}
