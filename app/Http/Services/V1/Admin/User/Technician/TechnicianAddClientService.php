<?php

namespace App\Http\Services\V1\Admin\User\Technician;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\ClientTechnician;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TechnicianAddClientService extends Singleton
{
    public function mount(Component $component, Technician $model)
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
                "clientsRelated" => $model->clientTechnicians
            ];
        }

        return [
            'network_operator_id' => null,
            'admins' => [],
            "clients" => [],
            'picked' => false,
            'model' => $model,
            "clientsRelated" => $model->clientTechnicians
        ];
    }


    public function updatedClient(Component $component)
    {
        $component->client_picked = false;
        $user = Auth::user()->getUserModel();
        $component->message_client = "No se encontraron clientes para este filtro";
        if ($component->client != "") {
            $component->clients = $user->clients()
                ->where(function (Builder $query) use ($component) {
                    return $query->where("identification", "like", '%' . $component->client . "%")
                        ->orWhere("name", "like", '%' . $component->client . "%")
                        ->orWhere("code", "like", '%' . $component->client . "%");
                })->take(3)->get();
        }
    }

    public function assignClient(Component $component, $client)
    {
        $obj = json_decode($client);
        $component->client = $obj->id . " - " . $obj->name;
        $component->client_id = $obj->id;
        $component->picked = true;
        $component->client_picked = true;
    }


    public function setNetworkOperatorId(Component $component, $admin)
    {
        $component->picked = true;
        $component->network_operator_id = $admin->id;
    }


    public function addClient(Component $component)
    {
        DB::transaction(function () use ($component) {
            if (!$component->client_picked) {
                return;
            }
            if ($component->model->clientTechnicians()->whereClientId($component->client_id)->exists()) {
                $this->refreshClientSeller($component);
                return;
            }
            $last_technician = ClientTechnician::whereClientId($component->client_id)->get();
            foreach ($last_technician as $technician){
                $technician->delete();
            }
            $component->model->clientTechnicians()->create(
                [
                    "active" => true,
                    "client_id" => $component->client_id
                ]
            );
            $this->refreshClientSeller($component);
        });
    }

    public function refreshClientSeller(Component $component)
    {
        $component->clientsRelated = $component->model->clientTechnicians()->get();
        $component->client = null;
        $component->client_id = null;
        $component->picked = false;
        $component->client_picked = false;
    }

    public function delete(Component $component, $clientId)
    {
        $component->model->clientTechnicians()->whereClientId($clientId)->delete();
        $this->refreshClientSeller($component);
        ToastEvent::launchToast($component, "show", "success", "Cliente desvinculado");
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
