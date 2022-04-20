<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Livewire\V1\Admin\User\EditUser;
use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\ClientType;
use App\Models\V1\Consumer;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function session;

class EditClientService extends Singleton
{
    public function mount(Component $component, Client $client)
    {
        $component->fill([
            'client' => $client,
            'identification' => $client->identification,
            'name' => $client->name,
            'email' => $client->email,
            'phone' => $client->phone,
            'direction' => $client->direction,
            'latitude' => $client->latitude,
            'longitude' => $client->longitude,
            'contribution' => $client->contribution,
            'public_lighting_tax' => $client->public_lighting_tax,
            'active_client' => $client->active_client,
            'network_operator_id' => $client->networkOperator->id,
            'network_operator' => $client->networkOperator->user->identification,
            'network_operators' => [],
            'picked_network_operator' => true,
            'message_network_operator' => "Ingrese identificacion de operador de red",
            'departments' => [], //Department::get(),
            'department_id' => $client->department_id,
            'municipalities' => [],//$client->department->municipalities,
            'municipality_id' => $client->municipality_id,
            'location_types' => [],//LocationType::get(),
            'location_id' => $client->location_id,
            'location_type_id' => "",//$client->location->location_type_id,
            'locations' => [],//Location::whereMunicipalityId($client->municipality_id)
                //->whereLocationTypeId($client->location->location_type_id)
                //->get(),
            'subsistence_consumptions' => SubsistenceConsumption::get(),
            'subsistence_consumption_id' => $client->subsistence_consumption_id,
            'voltage_levels' => VoltageLevel::get(),
            'voltage_level_id' => $client->voltage_level_id,
            'strata' => Stratum::get(),
            'stratum_id' => $client->stratum_id,
            'network_topology' => $client->network_topology,
        ]);
        $component->topologies = [];
        array_push($component->topologies, [
            "id" => Client::MONOPHASIC,
            "value" => "MONOFASICO",
        ]);
        array_push($component->topologies, [
            "id" => Client::BIPHASIC,
            "value" => "BIFASICO",
        ]);
        array_push($component->topologies, [
            "id" => Client::TRIPHASIC,
            "value" => "TRIFASICO",
        ]);
    }
    public function updatedLocationTypeId(Component $component)
    {
        $component->location_id = "";
        if ($component->municipality_id != "") {
            $component->locations = Location::whereMunicipalityId($component->municipality_id)
                ->whereLocationTypeId($component->location_type_id)
                ->get();
        } else {
            $component->locations = [];
        }
    }

    public function updatedDepartmentId(Component $component)
    {
        $component->municipality_id = "";
        $component->municipalities = Department::find($component->department_id)->municipalities;
    }

    public function updatedMunicipalityId(Component $component)
    {
        $component->location_id = "";
        if ($component->location_type_id != "") {
            $component->locations = Location::whereMunicipalityId($component->municipality_id)
                ->whereLocationTypeId($component->location_type_id)
                ->get();
        } else {
            $component->locations = [];
        }
    }

    public function updatedNetworkOperator(Component $component)
    {
        $component->picked_network_operator = false;
        $component->message_network_operator = "No hay operador de red registrado con esta identificación";

        if ($component->network_operator != "") {
            $component->network_operators = User::role('network_operator')->where("identification", "like", '%' . $component->network_operator . "%")
                ->take(3)->get();
        }
    }
    public function assignNetworkOperator(Component $component, $network_operator)
    {
        $obj = json_decode($network_operator);
        $component->network_operator = $obj->identification;
        $component->network_operator_id = User::find($obj->id)->networkOperator->id;
        $component->picked_network_operator= true;
    }

    public function submitForm(Component $component)
    {
        $component->client->fill($this->mapper($component));
        $component->client->update();
        $component->emitTo('livewire-toast', 'show', "Cliente {$component->client->name} actualizado exitosamente");
    }

    private function mapper(Component $component)
    {
        return [
            'name' => $component->name,
            'email' => $component->email,
            'phone' => $component->phone,
            'identification' => $component->identification,
            'latitude' => $component->latitude,
            'longitude' => $component->longitude,
            'direction' => $component->direction,
            'network_topology' => $component->network_topology,
            'active_client' => $component->active_client,
            'contribution' => $component->contribution,
            'public_lighting_tax' => $component->public_lighting_tax??true,
            'network_operator_id' =>$component->network_operator_id,
            'department_id' => $component->department_id,
            'municipality_id' => $component->municipality_id,
            'location_id' => $component->location_id,
            'subsistence_consumption_id' => $component->subsistence_consumption_id??1,
            'voltage_level_id' => $component->voltage_level_id,
            'stratum_id' => $component->stratum_id,
        ];
    }
}
