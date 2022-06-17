<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Livewire\V1\Admin\User\EditUser;
use App\Http\Services\Singleton;
use App\Models\Traits\ClientServiceTrait;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function session;

class EditClientService extends Singleton
{
    use ClientServiceTrait;

    public function mount(Component $component, Client $client)
    {

        $billingInformation = $this->getBillingInformation($client);
        $clientAddress = $this->getClientAddress($client);
        $component->fill([
            'client' => $client,
            'identification' => $client->identification,
            'name' => $client->name,
            'last_name' => $client->last_name,
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


            "network_topologies" => $this->topologies(),
            "network_topology" => "monophasic",
            'serials' => collect([]),
            'equipment' => [],
            "has_telemetry" => false,
            "create_supervisor" => false,
            "technician_select_disabled" => true,
            "stratum_id" => Stratum::first() ? Stratum::first()->id : null,
            'technicians' => [],
            'strata' => Stratum::get(),
            'client_types' => ClientType::get(),
            "technician_id" => null,
            'client_type_id' => ClientType::first() ? ClientType::first()->id : null,
            'voltage_levels' => VoltageLevel::get(),
            'subsistence_consumptions' => SubsistenceConsumption::get(),
            'location_types' => LocationType::get(),
            'locations' => [],
            "identification_type" => Client::IDENTIFICATION_TYPE_CC,
            "person_type" => Client::PERSON_TYPE_NATURAL,
            "identification_types" => $this->identificationTypes(),
            'person_types' => [
                ["key" => "Persona natural", "value" => Client::PERSON_TYPE_NATURAL,],
                ["key" => "Persona juridica", "value" => Client::PERSON_TYPE_JURIDICAL,]

            ],

            'subsistence_consumption_id' => $client->subsistence_consumption_id,
            'voltage_level_id' => $client->voltage_level_id,

            "billing_name" => $billingInformation ? $billingInformation->name : "",
            "billing_address" => $billingInformation ? $billingInformation->address : "",
            "addressDetails" => $clientAddress ? $clientAddress->details : "",
            "decodedAddress" => $clientAddress ? $clientAddress->address : "",
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
        $component->picked_network_operator = true;
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->client->fill($this->mapper($component));
            $component->client->update();
            $this->linkAddress($component, $component->client);
            $this->linkBillingInformation($component, $component->client);
            $component->redirectRoute("v1.admin.client.detail.client", ["client" => $component->client->id]);

        });
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
            'public_lighting_tax' => $component->public_lighting_tax ?? true,
            'network_operator_id' => $component->network_operator_id,
            'subsistence_consumption_id' => $component->subsistence_consumption_id ?? 1,
            'voltage_level_id' => $component->voltage_level_id,
            'stratum_id' => $component->stratum_id,

        ];
    }

    private function linkAddress(Component $component, Client $client)
    {
        if ($address = $client->addresses()->first()) {

            $address->update([
                "latitude" => $component->latitude,
                "longitude" => $component->longitude,
                "details" => $component->addressDetails,
            ]);
            return;
        }
        $client->addresses()->create([
            "latitude" => $component->latitude,
            "longitude" => $component->longitude,
            "details" => $component->addressDetails,
        ]);

    }

    private function linkBillingInformation(Component $component, Client $client)
    {
        if ($billingInformation = $client->billingInformation()->first()) {
            $billingInformation->update([
                "address" => $component->billing_address,
                "phone" => $client->phone,
                "identification" => $client->identification,
                "identification_type" => $client->identification_type,
                "name" => $component->billing_name,
                "default" => true
            ]);
            return;
        }
        $client->billingInformation()->create([
            "address" => $component->billing_address,
            "phone" => $client->phone,
            "identification" => $client->identification,
            "identification_type" => $client->identification_type,
            "name" => $component->billing_name,
            "default" => true
        ]);

    }
}
