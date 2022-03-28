<?php

namespace App\Http\Services\V1\Admin\User\Supervisor;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\Supervisor;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SupervisorAddClientService extends Singleton
{
    public function mount(Component $component, Supervisor $model)
    {
        $component->fill($this->getMountData($model));
    }

    private function getMountData($model)
    {
        $user = Auth::user();
        if ($user->hasRole(User::TYPE_NETWORK_OPERATOR)) {
            return [
                'admins' => [],
                "clients" => [],
                'picked' => false,
                'model' => $model
            ];
        }

        return [
            'network_operator_id' => null,
            'admins' => [],
            "clients" => [],
            'picked' => false,
            'model' => $model
        ];
    }


    public function updatedClient(Component $component)
    {

        $component->picked_client = false;
        $component->message_client = "No se encontraron clientes para este filtro";
        if ($component->client != "") {
            $component->clients = Client::where("identification", "like", '%' . $component->client . "%")
                ->orWhere("name", "like", '%' . $component->client . "%")
                ->take(3)->get();
        }
    }

    public function assignClient(Component $component, $client)
    {
        $obj = json_decode($client);
        $component->client = $obj->identification . " - " . $obj->name;
        $component->client_id = $obj->id;
        $component->picked = true;
    }


    public function setNetworkOperatorId(Component $component, $admin)
    {
        $component->picked = true;
        $admin = json_decode($admin);
        $component->network_operator_id = $admin->id;
    }


    public function addClient(Component $component)
    {
        $client = Client::first($component->client_id);
        $component->model->clients()->save($client);
        $component->redirectRoute("administrar.v1.usuarios.supervisores.detalles", ["supervisor" => $component->model->id]);
    }


    private function mapper($component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "network_operator_id" => $component->network_operator_id,
            "identification" => $component->identification
        ];
    }
}
