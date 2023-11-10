<?php

namespace App\Http\Services\V1\Admin\User\Seller;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Services\Singleton;
use App\Models\V1\Seller;
use Livewire\Component;

class SellerEditService extends Singleton
{
    public function mount(Component $component, Seller $model)
    {
        $component->fill([
            'model' => $model,
            'name' => $model->name,
            'last_name' => $model->last_name,
            'phone' => $model->phone,
            'email' => $model->email,
            'indicative' => $model->indicative,
            'identification' => $model->identification,
            "indicatives" => IndicativeHelper::getIndicativesKeyValue()

        ]);
    }

    public function submitForm(Component $component)
    {
        $component->model->fill($this->mapper($component));
        $component->model->update();
        $component->redirectRoute("administrar.v1.usuarios.vendedores.detalles", ["seller" => $component->model->id]);
    }

    private function mapper(Component $component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "identification" => $component->identification
        ];
    }
}
