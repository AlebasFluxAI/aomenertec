<?php

namespace App\Http\Services\V1\Admin\User\Supervisor;

use App\Http\Services\Singleton;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Supervisor;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SupervisorAddService extends Singleton
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

        return [
            'network_operator_id' => null,
            'admins' => [],
            "network_operators" => [],
            'picked' => false
        ];
    }

    public function submitForm(Component $component)
    {
        $component->validate();

        $supervisor = Supervisor::create($this->mapper($component));
        $user = User::create(array_merge($this->mapper($component), [
            "password" => bcrypt($component->password),
            "type" => User::TYPE_SUPERVISOR
        ]));
        $supervisor->update([
            "user_id" => $user->id
        ]);

        $component->redirectRoute("administrar.v1.usuarios.supervisores.detalles", ["supervisor" => $supervisor->id]);
    }

    private function mapper($component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "identification" => $component->identification,
            "network_operator_id" => $component->network_operator_id,

        ];
    }

    public function updatedNetworkOperator(Component $component)
    {
        $component->picked_network_operator = false;
        $component->message_network_operator = "No hay operador de red registrado con esta identificación";
        if ($component->network_operator != "") {
            $component->network_operators = NetworkOperator::where("identification", "like", '%' . $component->network_operator . "%")
                ->orWhere("name", "like", '%' . $component->network_operator . "%")
                ->take(3)->get();
        }
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
}
