<?php

namespace App\Http\Services\V1\Admin\User\Technician;

use App\Http\Resources\V1\Menu;
use App\Http\Services\Singleton;
use App\Models\V1\Technician;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TechnicianAddService extends Singleton
{
    public function mount(Component $component)
    {
        $component->fill($this->getMountData());
    }

    private function getMountData()
    {
        $user = Auth::user();
        if ($user->hasRole(User::TYPE_NETWORK_OPERATOR)) {
            return [
                'admins' => [],
                "network_operators" => [],
                "network_operator_id" => $user->networkOperator->id,
                'picked' => false
            ];
        }
        $model = Menu::getUserModel();
        return [
            'network_operator_id' => null,
            'admins' => [],
            "network_operators" => $model->networkOperatorsAsKeyValue(),
            'picked' => false
        ];
    }


    public function assignNetworkOperator(Component $component, $network_operator)
    {
        $obj = json_decode($network_operator);
        $component->network_operator = $obj->identification . " - " . $obj->name;
        $component->network_operator_id = $obj->id;
        $component->picked = true;
    }

    public function setNetworkOperatorId(Component $component, $admin)
    {
        $component->picked = true;
        $admin = json_decode($admin);
        $component->network_operator_id = $admin->id;
    }


    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->validate();

            $seller = Technician::create($this->mapper($component));
            $user = User::create(array_merge($this->mapper($component), [
                "password" => bcrypt($component->password),
                "type" => User::TYPE_TECHNICIAN
            ]));
            $seller->update([
                "user_id" => $user->id
            ]);

            $component->redirectRoute("administrar.v1.usuarios.tecnicos.detalles", ["technician" => $seller->id]);
        });
    }


    private function mapper($component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "network_operator_id" => $component->network_operator_id,
            "identification" => $component->identification,
            "latitude" => $component->latitude,
            "longitude" => $component->longitude,
        ];
    }
}
