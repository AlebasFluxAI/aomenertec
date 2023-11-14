<?php

namespace App\Http\Services\V1\Admin\User\Support;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\Support;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SupportAddClientService extends Singleton
{
    public function mount(Component $component, Support $model)
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
                'model' => $model,
                "clientsRelated" => $model->clientSupports,
            ];
        }

        return [
            'network_operator_id' => null,
            'admins' => [],
            "clients" => [],
            'picked' => false,
            'model' => $model,
            "clientsRelated" => $model->clientSupports,
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
        $component->client_picked = true;
    }


    public function setNetworkOperatorId(Component $component, $admin)
    {
        $component->picked = true;
        $admin = json_decode($admin);
        $component->network_operator_id = $admin->id;
    }


    public function addClient(Component $component)
    {
        DB::transaction(function () use ($component) {
            if ($component->model->clientSupports()->whereClientId($component->client_id)->exists()) {
                $this->refreshClients($component);

                return;
            }
            $component->model->clientSupports()->create(
                [
                    "active" => true,
                    "client_id" => $component->client_id
                ]
            );
            $this->refreshClients($component);
        });
    }

    public function refreshClients(Component $component)
    {
        $component->clientsRelated = $component->model->clientSupports()->get();
        $component->client = null;
        $component->client_id = null;
        $component->picked = false;
        $component->client_picked = false;
    }

    public function delete(Component $component, $client)
    {
        if (!$client) {
            foreach ($component->model->clientSupports as $clientSupport) {
                if (!Client::whereId($clientSupport->client_id)->exists()) {
                    $component->model->clientSupports()->whereClientId($clientSupport->client_id)->delete();
                }
            }
            $this->refreshClients($component);
            return;
        }
        $clientId = json_decode($client, true)["id"];
        $component->model->clientSupports()->whereClientId($clientId)->delete();
        $this->refreshClients($component);
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
