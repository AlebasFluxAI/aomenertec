<?php

namespace App\Http\Services\V1\Admin\User\Seller;

use App\Http\Services\Singleton;
use Livewire\Component;

class SellerDetailsService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }

    public function edit(Component $component)
    {
        $component->redirectRoute("administrar.v1.usuarios.vendedores.editar", ["supervisor" => $component->model->id]);
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.vendedores.detalles", ["supervisor" => $modelId]);
    }
}
