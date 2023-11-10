<?php

namespace App\Http\Services\V1\Admin\User\Supervisor;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Services\Singleton;
use App\Models\Traits\AddUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\Supervisor;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SupervisorAddService extends Singleton
{
    use AddUserFormTrait;

    public function mount(Component $component)
    {
        $component->fill([
            "form_tittle" => "Datos del tecnico",
            "decodedAddress" => "",
            "identification_types" => $this->identificationTypes(User::PERSON_TYPE_NATURAL),
            'person_types' => [
                ["key" => "Persona natural", "value" => User::PERSON_TYPE_NATURAL],
                ["key" => "Persona juridica", "value" => User::PERSON_TYPE_JURIDICAL]
            ],
            "admins" => (Auth::user()->superAdmin) ? Auth::user()->superAdmin->adminsAsKeyValue() : ((Auth::user()->admin) ? [] : []),
            "admin_id" => (Auth::user()->superAdmin) ? "" : ((Auth::user()->admin) ? Auth::user()->admin->id : Auth::user()->networkOperator->admin->id),
            "network_operators" => (Auth::user()->superAdmin) ? [] : ((Auth::user()->admin) ? Auth::user()->admin->networkOperatorsAsKeyValue() : []),
            "model.network_operator_id" => (Auth::user()->networkOperator) ? Auth::user()->networkOperator->id : "",
            'model.person_type' => User::PERSON_TYPE_NATURAL,
            "model.identification_type" => User::IDENTIFICATION_TYPE_CC,
            "model.billing_name" => "",
            "model.last_name" => "",
            "model.name" => "",
            "indicatives" => IndicativeHelper::getIndicativesKeyValue()

        ]);
    }

    public function updatedAdminId(Component $component)
    {
        $component->network_operators = Admin::find($component->admin_id)->networkOperatorsAsKeyValue();
    }

    public function submitForm(Component $component)
    {
        $component->validate([
            'sign' => 'image|max:10240', // 1MB Max
        ]);

        DB::transaction(function () use ($component) {
            $component->model['latitude'] = $component->latitude;
            $component->model['longitude'] = $component->longitude;
            $component->validate();
            $supervisor = Supervisor::create($component->model);
            $user = User::create($this->mapper($component));
            $supervisor->update([
                "user_id" => $user->id
            ]);
            $supervisor->buildOneImageFromFile("sign", $component->sign);
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$supervisor->name} creado"]);
            $component->redirectRoute("administrar.v1.usuarios.supervisores.detalles", ["supervisor" => $supervisor->id]);
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
            "type" => User::TYPE_SUPERVISOR
        ];
    }
}
