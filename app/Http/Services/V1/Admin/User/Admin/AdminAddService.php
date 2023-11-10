<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Services\Singleton;
use App\Models\Traits\AddUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminAddService extends Singleton
{
    use AddUserFormTrait;

    public function mount(Component $component)
    {
        $component->fill([
            "personTypes" => [],
            "styles" => Admin::styles(),
            "decodedAddress" => "",
            "identification_types" => $this->identificationTypes(User::PERSON_TYPE_NATURAL),
            'person_types' => [
                ["key" => "Persona natural", "value" => User::PERSON_TYPE_NATURAL],
                ["key" => "Persona juridica", "value" => User::PERSON_TYPE_JURIDICAL]
            ],
            'model.person_type' => User::PERSON_TYPE_NATURAL,
            "model.identification_type" => User::IDENTIFICATION_TYPE_CC,
            "model.billing_name" => "",
            "model.last_name" => "",
            "model.name" => "",
            "indicatives" => IndicativeHelper::getIndicativesKeyValue(),
            "model.indicative" => IndicativeHelper::COLOMBIA
        ]);
    }


    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->validate([
                'icon' => 'image|max:10240', // 1MB Max
            ]);
            $component->model['latitude'] = $component->latitude;
            $component->model['longitude'] = $component->longitude;
            $component->validate();
            $admin = Admin::create($component->model);
            $admin->buildOneImageFromFile("icon", $component->icon);
            $user = User::create($this->mapper($component));
            $admin->update([
                "user_id" => $user->id
            ]);

            if ($component->user_type_network_operator) {
                $networkOperator = $component->createNetworkOperator($user->id, $component, $admin->id);
            }
            if ($component->user_type_technician) {
                $component->createTechnician($user->id, $component, $admin->id, $networkOperator->id);
            }

            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$admin->name} creado"]);
            $component->redirectRoute("administrar.v1.usuarios.admin.detalles", ["admin" => $admin->id]);
        });
    }


    private function mapper($component)
    {
        return [
            "name" => $component->model['name'],
            "last_name" => $component->model['last_name'],
            "email" => $component->model['email'],
            "phone" => $component->model['phone'],
            "indicative" => $component->model['indicative'],
            "identification" => $component->model['identification'],
            "type" => User::TYPE_ADMIN
        ];
    }

    public function setStyle()
    {
    }
}
