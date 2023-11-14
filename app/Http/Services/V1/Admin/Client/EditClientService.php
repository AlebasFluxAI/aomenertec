<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Resources\V1\TimeZoneHelper;
use App\Http\Services\Singleton;
use App\Models\Traits\ClientServiceTrait;
use App\Models\V1\Client;
use App\Models\V1\Consumer;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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
            'alias' => $client->alias,
            'indicative' => $client->indicative,
            'time_zone' => $client->time_zone,
            'email' => $client->email,
            'phone' => $client->phone,
            'direction' => $clientAddress->direction,
            'latitude' => $clientAddress->latitude,
            'longitude' => $clientAddress->longitude,
            'address_details' => $clientAddress->details,
            'contribution' => $client->contribution,
            'public_lighting_tax' => $client->public_lighting_tax,
            'active_client' => $client->active_client,
            'network_operator_id' => $client->networkOperator ? $client->networkOperator->id : null,
            'network_operator' => $client->networkOperator ? $client->networkOperator->user->identification : null,
            "network_topologies" => $this->topologies(),
            "network_topology" => $client->network_topology,
            'serials' => collect([]),
            'equipment' => [],
            "has_telemetry" => false,
            "create_supervisor" => false,
            "technician_select_disabled" => true,
            "stratum_id" => Stratum::first() ? Stratum::first()->id : null,
            'strata' => Stratum::get(),
            'technicians' => $this->getTechnicians($component),
            'client_types' => $this->getClientTypes($component),
            "technician_id" => null,
            'client_type_id' => $client->clientType->id,
            'voltage_levels' => VoltageLevel::get(),
            'subsistence_consumptions' => SubsistenceConsumption::get(),
            "identification_type" => Client::IDENTIFICATION_TYPE_CC,
            "person_type" => Client::PERSON_TYPE_NATURAL,
            "identification_types" => $this->identificationTypes(),
            'person_types' => [
                ["key" => "Persona natural", "value" => Client::PERSON_TYPE_NATURAL,],
                ["key" => "Persona juridica", "value" => Client::PERSON_TYPE_JURIDICAL,]

            ],
            'network_operators' => $this->getNetworkOperators($component),
            'subsistence_consumption_id' => $client->subsistence_consumption_id,
            'voltage_level_id' => $client->voltage_level_id,
            "billing_name" => $billingInformation ? $billingInformation->name : "",
            "billing_address" => $billingInformation ? $billingInformation->address : "",
            "addressDetails" => $clientAddress ? $clientAddress->details : "",
            "decodedAddress" => $clientAddress ? $clientAddress->address : "",
            "indicatives" => IndicativeHelper::getIndicativesKeyValue(),
            "time_zones" => TimeZoneHelper::getTimeZoneKeyValue(),
            "stratification_name" => $client->vaupes_stratification_type,
            "stratification" => [
                ["key" => Client::RESIDENCE_1_41R, "value" => Client::RESIDENCE_1_41R],
                ["key" => Client::RESIDENCE_2_42R, "value" => Client::RESIDENCE_2_42R],
                ["key" => Client::RESIDENCE_3_43R, "value" => Client::RESIDENCE_3_43R],
                ["key" => Client::OFFICIAL_1_410, "value" => Client::OFFICIAL_1_410],
                ["key" => Client::OFFICIAL_2_420, "value" => Client::OFFICIAL_2_420],
                ["key" => Client::COMMERCIAL_1_41C, "value" => Client::COMMERCIAL_1_41C],
                ["key" => Client::COMMERCIAL_2_42C, "value" => Client::COMMERCIAL_2_42C],
                ["key" => Client::COMMERCIAL_3_43C, "value" => Client::COMMERCIAL_3_43C],
                ["key" => Client::SUSPENDED_R1_R2, "value" => Client::SUSPENDED_R1_R2],
            ]
        ]);
    }

    public function getTechnicians($component)
    {
        if (Auth::user()->networkOperator) {
            $component->technician_select_disabled = false;
            return Technician::whereNetworkOperatorId($component->network_operator_id)
                ->get()->map(function ($technician) {
                    return [
                        "key" => $technician->id . " - " . $technician->name . " - " . $technician->identification,
                        "value" => $technician->id
                    ];
                })->toArray();
        }
        $component->technician_select_disabled = true;
        return [];
    }

    private function getClientTypes($component)
    {
        if (Auth::user()->networkOperator) {
            $admin = Auth::user()->networkOperator->admin;
            return $admin->clientTypesAsKeyValue();
        }

        $admin = User::getUserModel();

        return $admin->clientTypesAsKeyValue();
    }

    private function getNetworkOperators($component)
    {
        if (Auth::user()->networkOperator) {
            $component->network_operator_id = Auth::user()->networkOperator->id;
            return [];
        }
        $admin = User::getUserModel();

        return $admin->networkOperatorsAsKeyValue();
    }

    public function updatedNetworkOperatorId(Component $component)
    {
        if (!$component->network_operator_id) {
            return;
        }
        $component->technician_select_disabled = false;
        $component->technicians = NetworkOperator::find($component->network_operator_id)->techniciansAsKeyValue();
        $component->technician_id = null;
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
            $this->linkTechnician($component, $component->client);
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
            'last_name' => $component->last_name,
            'alias' => $component->alias,
            'indicative' => $component->indicative,
            'time_zone' => $component->time_zone,
            'phone' => $component->phone,
            'identification' => $component->identification,
            'network_topology' => $component->network_topology,
            'active_client' => $component->active_client,
            'contribution' => $component->contribution,
            'public_lighting_tax' => $component->public_lighting_tax ?? true,
            'network_operator_id' => $component->network_operator_id,
            'subsistence_consumption_id' => $component->subsistence_consumption_id ?? 1,
            'voltage_level_id' => $component->voltage_level_id,
            'stratum_id' => $component->stratum_id,
            "vaupes_stratification_type" => $component->stratification_name,
        ];
    }

    private function linkTechnician(Component $component, Client $client)
    {
        if (!$component->technician_id) {
            return;
        }
        $client->technician()->delete();
        $client->technician()->create([
            "technician_id" => $component->technician_id
        ]);
    }

    private function linkAddress(Component $component, Client $client)
    {
        if ($address = $this->getClientAddress($client)) {
            $address->update([
                "latitude" => $component->latitude,
                "longitude" => $component->longitude,
                "details" => $component->address_details,
            ]);
            return;
        }
        $client->addresses()->create([
            "latitude" => $component->latitude,
            "longitude" => $component->longitude,
            "details" => $component->address_details,
        ]);
    }

    private function linkBillingInformation(Component $component, Client $client)
    {
        if ($billingInformation = $client->billingInformation()->first()) {
            $billingInformation->update([
                "address" => $component->billing_address,
                "phone" => $component->phone,
                "identification" => $component->identification,
                "identification_type" => $component->identification_type,
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
