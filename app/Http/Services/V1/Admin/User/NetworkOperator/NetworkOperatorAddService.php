<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Services\Singleton;
use App\Models\Traits\AddUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NetworkOperatorAddService extends Singleton
{
    use AddUserFormTrait;

    public function mount(Component $component)
    {
        $component->fill([
            "form_tittle" => "Datos del operador de red",
            "decodedAddress" => "",
            "identification_types" => $this->identificationTypes(User::PERSON_TYPE_NATURAL),
            'person_types' => [
                ["key" => "Persona natural", "value" => User::PERSON_TYPE_NATURAL],
                ["key" => "Persona juridica", "value" => User::PERSON_TYPE_JURIDICAL]
            ],
            "admins" => (Auth::user()->admin) ? [] : Admin::get(),
            "model.admin_id" => (Auth::user()->admin) ? Auth::user()->admin->id : Admin::first()->id,
            'model.person_type' => User::PERSON_TYPE_NATURAL,
            "model.identification_type" => User::IDENTIFICATION_TYPE_CC,
            "model.billing_name" => "",
            "model.last_name" => "",
            "model.name" => "",
            "indicatives" => IndicativeHelper::getIndicativesKeyValue()

        ]);
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->model['latitude'] = $component->latitude;
            $component->model['longitude'] = $component->longitude;
            $component->validate();
            $operator = NetworkOperator::create($component->model);
            $user = User::create($this->mapper($component));
            $operator->update([
                "user_id" => $user->id
            ]);
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$operator->name} creado"]);

            $component->redirectRoute("administrar.v1.usuarios.operadores.detalles", ["networkOperator" => $operator->id]);
        });
    }

    private function mapper($component)
    {
        return [
            "name" => $component->model['name'],
            "last_name" => $component->model['last_name'],
            "email" => $component->model['email'],
            "phone" => $component->model['phone'],
            "identification" => $component->model['identification'],
            "type" => User::TYPE_NETWORK_OPERATOR
        ];
    }
}
