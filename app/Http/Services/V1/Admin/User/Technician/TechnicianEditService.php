<?php

namespace App\Http\Services\V1\Admin\User\Technician;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Services\Singleton;
use App\Models\Traits\AddUserFormTrait;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TechnicianEditService extends Singleton
{
    use AddUserFormTrait;

    public function mount(Component $component, Technician $model)
    {
        $component->fill([
            "decodedAddress" => $model->address,
            "identification_types" => $this->identificationTypes($model->person_type),
            'person_types' => [
                ["key" => "Persona natural", "value" => User::PERSON_TYPE_NATURAL],
                ["key" => "Persona juridica", "value" => User::PERSON_TYPE_JURIDICAL]
            ],
            "model" => $model,
            "latitude" => $model->latitude,
            "longitude" => $model->longitude,
            "indicatives" => IndicativeHelper::getIndicativesKeyValue()

        ]);
    }


    public function submitForm(Component $component)
    {
        $component->validate([
            'sign' => 'image|max:10240', // 1MB Max
        ]);

        DB::transaction(function () use ($component) {
            $component->model->latitude = $component->latitude;
            $component->model->longitude = $component->longitude;
            $component->validate([
                'model.identification' => 'required|min:6',
                'model.name' => 'required|min:6',
                'model.last_name' => 'required|min:6',
                'model.phone' => 'min:7',
                'model.email' => 'required|email',
                'model.address_details' => 'required',
                'model.latitude' => 'required',
                'model.longitude' => 'required',
                'model.billing_name' => 'required',
                'model.billing_address' => 'required',
                'model.person_type' => 'required',
                'model.identification_type' => 'required',
            ]);
            $component->model->update();
            $component->model->buildOneImageFromFile("sign", $component->sign);

            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$component->model->name} actualizado"]);
            $component->redirectRoute("administrar.v1.usuarios.tecnicos.detalles", ["technician" => $component->model->id]);
        });
    }
}
