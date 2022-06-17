<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Services\Singleton;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NetworkOperatorEditService extends Singleton
{
    public function mount(Component $component, NetworkOperator $model)
    {
        $component->fill([
            'model' => $model,
            'name' => $model->name,
            'last_name' => $model->last_name,
            'phone' => $model->phone,
            'email' => $model->email,
            'identification' => $model->identification,
            'latitude' => $model->latitude,
            'longitude' => $model->longitude,
            "addressDetails" => $model->address_details,
            "decodedAddress" => $model->address,
        ]);
    }


    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->model->fill($this->mapper($component));
            $component->model->update();
            $component->redirectRoute("administrar.v1.usuarios.operadores.detalles", ["networkOperator" => $component->model->id]);
        });
    }

    private function mapper(Component $component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "identification" => $component->identification,
            "latitude" => $component->latitude,
            "longitude" => $component->longitude,
            "address_details" => $component->addressDetails,
        ];
    }
}
