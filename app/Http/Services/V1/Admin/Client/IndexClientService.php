<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Livewire\V1\Admin\Client\AddClient;
use App\Http\Services\Singleton;
use App\Models\V1\EquipmentClient;
use App\Models\V1\ClientType;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Client;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class IndexClientService extends Singleton
{
    public function delete(Component $component, $clientId)
    {
        Client::find($clientId)->delete();
        $component->emitTo('livewire-toast', 'show', "Equipo {$clientId} eliminado exitosamente");
        $component->reset();
    }

    public function getClients()
    {
        return Client::get()->paginate(15);
    }

    public function edit(Component $component, $clientId)
    {
        $component->redirectRoute("v1.admin.client.edit.client", ["client" => $clientId]);
    }

    public function conditionalMonitoring(Component $component, $modelId)
    {
        return !MicrocontrollerData::whereClientId($modelId)->exists();
    }


    public function details(Component $component, $clientId)
    {
        $component->redirectRoute("v1.admin.client.detail.client", ["client" => $clientId]);
    }

    public function settings(Component $component, $clientId)
    {
        $component->redirectRoute("v1.admin.client.settings", ["client" => $clientId]);
    }

    public function getData(Component $component)
    {
        $user = Auth::user();
        if ($networkOperator = $user->networkOperator) {
            if ($component->filter) {
                return $networkOperator->clients()->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
            }
            return $networkOperator->clients()->paginate(15);
        }

        if ($admin = $user->admin) {
            if ($component->filter) {
                return Client::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
            }
            return Client::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))->paginate(15);
        }


        if ($component->filter) {
            return Client::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
        }

        return Client::paginate(15);
    }
}
