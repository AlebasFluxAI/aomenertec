<?php

namespace App\Http\Services\V1\Admin\User\Support;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Services\Singleton;
use App\Models\V1\Support;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SupportEditService extends Singleton
{
    public function mount(Component $component, Support $model)
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

    private function identificationTypes($person_type)
    {
        if ($person_type == User::PERSON_TYPE_NATURAL) {
            return [
                [
                    "key" => User::IDENTIFICATION_TYPE_CC,
                    "value" => User::IDENTIFICATION_TYPE_CC,
                ],
                [
                    "key" => User::IDENTIFICATION_TYPE_CE,
                    "value" => User::IDENTIFICATION_TYPE_CE,
                ],
                [
                    "key" => User::IDENTIFICATION_TYPE_PEP,
                    "value" => User::IDENTIFICATION_TYPE_PEP,
                ],
                [
                    "key" => User::IDENTIFICATION_TYPE_PP,
                    "value" => User::IDENTIFICATION_TYPE_PP,
                ],
                [
                    "key" => User::IDENTIFICATION_TYPE_NIT,
                    "value" => User::IDENTIFICATION_TYPE_NIT,
                ],
            ];
        } else {
            return [
                [
                    "key" => User::IDENTIFICATION_TYPE_NIT,
                    "value" => User::IDENTIFICATION_TYPE_NIT,
                ],
                [
                    "key" => User::IDENTIFICATION_TYPE_OTHER,
                    "value" => "OTRO"
                ],

            ];
        }
    }


    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->model->latitude = $component->latitude;
            $component->model->longitude = $component->longitude;
            $component->model->update();
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$component->model->name} actualizado"]);
            $component->redirectRoute("administrar.v1.usuarios.soporte.detalles", ["support" => $component->model->id]);
        });
    }
}
