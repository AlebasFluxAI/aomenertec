<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Livewire\V1\Admin\Client\AddClient;
use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Traits\ClientServiceTrait;
use App\Models\V1\BillingInformation;
use App\Models\V1\ClientSupervisor;
use App\Models\V1\EquipmentClient;
use App\Models\V1\ClientType;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Client;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class AddClientService extends Singleton
{
    use ClientServiceTrait;

    public function mount(Component $component)
    {
        $component->fill([
            "network_topologies" => $this->topologies(),
            "network_topology" => "monophasic",
            'serials' => collect([]),
            'equipment' => [],
            "has_telemetry" => false,
            "create_supervisor" => false,
            "stratum_id" => Stratum::first() ? Stratum::first()->id : null,
            'network_operators' => $this->getNetworkOperators($component),
            'technicians' => $this->getTechnicians($component),
            'strata' => Stratum::get(),
            'client_types' => $this->getClientTypes($component),
            "technician_id" => 0,
            'client_type_id' => 0,
            'voltage_levels' => VoltageLevel::get(),
            'subsistence_consumptions' => SubsistenceConsumption::get(), 'contribution' => true,

            "identification_type" => Client::IDENTIFICATION_TYPE_CC,
            "person_type" => Client::PERSON_TYPE_NATURAL,
            "identification_types" => $this->identificationTypes(),
            'person_types' => [
                ["key" => "Persona natural", "value" => Client::PERSON_TYPE_NATURAL,],
                ["key" => "Persona juridica", "value" => Client::PERSON_TYPE_JURIDICAL,]

            ],
            'municipalities' => [],
            'equipment_types' => [],
            'network_operator_id' => Auth::user()->networkOperator ? Auth::user()->networkOperator->id : null,
            'picked_network_operator' => false, 'message_network_operator' => 'Digite identificación del operador de red',
            'picked_aux_network_operator' => false, 'message_aux_network_operator' => 'Digite identificación del operador de red',
            'aux_network_operators' => [],
        ]);
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

    private function getClientTypes($component)
    {
        if (Auth::user()->networkOperator) {
            $admin = Auth::user()->networkOperator->admin;
            return $admin->clientTypesAsKeyValue();
        }

        $admin = User::getUserModel();

        return $admin->clientTypesAsKeyValue();
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


    public function updatedPersonType(Component $component)
    {
        $component->identification_types = match ($component->person_type) {
            Client::PERSON_TYPE_JURIDICAL => [
                [
                    "key" => Client::IDENTIFICATION_TYPE_NIT,
                    "value" => "NIT"
                ]
            ],
            Client::PERSON_TYPE_NATURAL => [
                [
                    "key" => Client::IDENTIFICATION_TYPE_CC,
                    "value" => Client::IDENTIFICATION_TYPE_CC,
                ],
                [
                    "key" => Client::IDENTIFICATION_TYPE_CE,
                    "value" => Client::IDENTIFICATION_TYPE_CE,
                ],
                [
                    "key" => Client::IDENTIFICATION_TYPE_PEP,
                    "value" => Client::IDENTIFICATION_TYPE_PEP,
                ],
                [
                    "key" => Client::IDENTIFICATION_TYPE_PP,
                    "value" => Client::IDENTIFICATION_TYPE_PP,
                ],
                [
                    "key" => Client::IDENTIFICATION_TYPE_NIT,
                    "value" => "NIT"
                ]
            ],
            default => []
        };
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

    public function updatedClientTypeId(Component $component)
    {
        if ($component->client_type_id != "") {
            $component->equipment = [];
            $component->client_type = ClientType::find($component->client_type_id);

            $component->equipment_types = $component->client_type->equipmentTypes;
            foreach ($component->equipment_types as $index => $type) {
                array_push($component->equipment, [
                    "index" => $index,
                    "id" => "",
                    "type_id" => $type->id,
                    "type" => $type->type,
                    "serial" => "",
                    "picked" => false,
                    "post" => "Digite serial de " . $type->type,
                    "disable" => true,
                ]);
                $component->serials = collect([]);
            }
            $component->has_telemetry = $this->hasTelemetry($component);
        }
    }

    public function hasTelemetry(Component $component)
    {
        return strpos(strtolower(ClientType::find($component->client_type_id) ? ClientType::find($component->client_type_id)->type : null), "telemetria");
    }

    public function updatedNetworkOperatorId(Component $component)
    {
        $component->technician_select_disabled = false;
        $component->technicians = NetworkOperator::find($component->network_operator_id)->techniciansAsKeyValue();
        $component->technician_id = null;
    }

    public function updatedTechnician(Component $component)
    {
        $component->picked_technician = false;
        $component->message_technician = "No hay operador de red registrado con esta identificación";

        if ($component->technician != "") {
            $component->technicians = Technician::where("identification", "like", '%' . $component->technician . "%")
                ->whereNetworkOperatorId($component->network_operator_id)
                ->get();
        }
    }


    public function updatedLatitude(Component $component)
    {
        $latlng = "{$component->latitude},{$component->longitude}";
        $heremap = null;
        $response = Http::get('https://revgeocode.search.hereapi.com/v1/revgeocode', [
            'at' => $latlng,
            'apiKey' => config("here.apiKey"),
        ]);

        if (200 == $response->status()) {
            $body = $response->json();

            if (array_key_exists('items', $body)) {
                $heremap = json_encode($body);
            }
        }


        $map = json_decode($heremap ?? '{}');


        try {
            $map = $map->items[0];
            $hereAddress = $map->address;


            $hereMap = json_decode($heremap, true);

            if (array_key_exists('items', $hereMap)) {
                if (count($hereMap['items']) > 0) {
                    if (array_key_exists('address', $hereMap['items'][0])) {
                        $component->decodedAddress = array_key_exists('label', $hereMap['items'][0]['address']) ? $hereMap['items'][0]['address']['label'] : "";
                    }
                }
            }
        } catch (Throwable $e) {
        }
    }

    public function addInputEquipment(Component $component)
    {
        $component->equipment_types = EquipmentType::whereSerialized(true)->get();
        array_push($component->equipment, [
            "index" => count($component->equipment),
            "id" => "",
            "type_id" => "",
            "type" => "",
            "serial" => "",
            "picked" => false,
            "post" => "Seleccione tipo de equipo",
            "disable" => false,
        ]);
    }

    public function deleteInputEquipment(Component $component)
    {
        $necessary_equipment = 0;
        if ($component->client_type) {
            $necessary_equipment = count($component->client_type->equipmentTypes);
        }
        $current_equipment = count($component->equipment);
        if ($current_equipment > $necessary_equipment) {
            array_pop($component->equipment);
        } else {
            session()->flash('no_delete', 'Los equipos actuales son obligatorios');
        }
    }

    public function updated(Component $component, $property_name, $value)
    {
        if (strpos($property_name, "serial") !== false) {
            $id = filter_var($property_name, FILTER_SANITIZE_NUMBER_INT);
            if ($component->equipment[$id]['type_id'] == "") {
                $component->equipment[$id]['post'] == "Seleccione tipo de equipo";
            } else {
                $component->equipment[$id]['picked'] = false;
                $component->equipment[$id]['post'] == "No registrado";
                $type_id = $component->equipment[$id]['type_id'];
                if (strlen($value) >= 2) {
                    if (!$component->technician_id) {
                        ToastEvent::launchToast($component, "show", "error", "Debes seleccionar un tecnico", ["duration" => "2s"]);
                        return;
                    }
                    $component->serials = Equipment::where([
                        ["serial", "like", '%' . $value . "%"],
                        ["equipment_type_id", $type_id],
                    ])->whereIn("id", Technician::find($component->technician_id)->equipments()->pluck("id"))
                        ->whereNotIn('assigned', [true])
                        ->whereNotIn("status", [Equipment::STATUS_DISREPAIR, Equipment::STATUS_REPAIR])
                        ->take(3)->get();
                    if (count($component->serials) == 0) {
                        $component->equipment[$id]['post'] = "No registrado";
                    }
                }
            }
        } elseif (strpos($property_name, "type_id") !== false) {
            $id = filter_var($property_name, FILTER_SANITIZE_NUMBER_INT);
            if ($value != 0) {
                $equipment_type = EquipmentType::find($value);
                $component->equipment[$id]['picked'] = false;
                $component->equipment[$id]['serial'] = "";
                $component->equipment[$id]['type_id'] = $equipment_type->id;
                $component->equipment[$id]['type'] = $equipment_type->type;
                $component->equipment[$id]['post'] = "Digite serial de " . $equipment_type->type;
            } else {
                $component->equipment[$id]['picked'] = false;
                $component->equipment[$id]['serial'] = "";
                $component->equipment[$id]['type_id'] = "";
                $component->equipment[$id]['type'] = "";
                $component->equipment[$id]['post'] = "Seleccione tipo de equipo";
            }
        }
    }

    public function assignEquipment(Component $component, $id, $aux)
    {
        $equipment = Equipment::find($id);
        $component->equipment[$aux]['serial'] = $equipment->serial;
        $component->equipment[$aux]['id'] = $equipment->id;
        $component->equipment[$aux]['picked'] = true;
        $component->equipment[$aux]['post'] = "";
    }

    public function assignEquipmentFirst(Component $component, $index)
    {
        if (strlen($component->equipment[$index]['serial']) >= 2) {
            $equipment_type = EquipmentType::find($component->equipment[$index]['type_id']);
            $equipment = Equipment::where([
                ["serial", "like", '%' . $component->equipment[$index]['serial'] . "%"],
                ["equipment_type_id", $equipment_type->id],
            ])->whereNotIn('assigned', [true])
                ->whereNotIn("status", [Equipment::STATUS_DISREPAIR, Equipment::STATUS_REPAIR])
                ->first();
            if ($equipment) {
                $component->equipment[$index]['serial'] = $equipment->serial;
                $component->equipment[$index]['id'] = $equipment->id;
                $component->equipment[$index]['post'] = "";
                $component->equipment[$index]['picked'] = true;
            } else {
                $component->solar_panel = "...";
            }
        }
    }

    public function save(Component $component)
    {

        //$component->validate();
        DB::transaction(function () use ($component) {
            $client = $this->createClient($component);
            $this->linkAddress($component, $client);
            $this->linkTechnician($component, $client);
            $this->linkEquipments($component, $client);
            $this->createSupervisor($component, $client);
            $this->createBillingInformation($component, $client);
            $component->redirectRoute("v1.admin.client.detail.client", ["client" => $client->id]);
        });
    }

    private function createClient($component)
    {
        while (true) {
            $code = $this->clientCode();
            if (!(Client::whereCode($code)->exists())) {
                break;
            }
        }
        return Client::create([
            'name' => $component->name,
            'last_name' => $component->last_name,
            'email' => $component->email,
            'code' => $code,
            'phone' => $component->phone,
            'identification' => $component->identification,
            'network_topology' => $component->network_topology,
            'active' => $component->active,
            'contribution' => $component->contribution,
            'public_lighting_tax' => $component->public_lighting_tax ?? true,
            'network_operator_id' => $component->network_operator_id,
            'client_type_id' => $component->client_type_id,
            'subsistence_consumption_id' => $component->subsistence_consumption_id ?? 1,
            'voltage_level_id' => $component->voltage_level_id,
            'stratum_id' => $component->stratum_id,
            'identification_type' => $component->identification_type,
            'person_type' => $component->person_type,
        ]);
    }

    public function clientCode($input = '0123456789', $strength = 10)
    {
        $input_length = strlen($input);
        $random_codigo = "";
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_codigo .= $random_character;
        }
        return $random_codigo;
    }

    private function linkAddress(Component $component, Client $client)
    {
        $client->addresses()->create([
            "latitude" => $component->latitude,
            "longitude" => $component->longitude,
            "details" => $component->addressDetails,
        ]);
    }

    private function linkTechnician(Component $component, Client $client)
    {
        $client->technician()->create([
            "technician_id" => $component->technician_id
        ]);
    }

    private function linkEquipments(Component $component, Client $client)
    {
        foreach ($component->equipment as $item) {
            if (!$item["id"]) {
                continue;
            }
            EquipmentClient::create([
                'client_id' => $client->id,
                'equipment_id' => $item['id'],
                'current_assigned' => true,
            ]);
            Equipment::find($item['id'])->update(['assigned' => true]);
        }
    }

    private function createSupervisor(Component $component, Client $client)
    {
        if (!$component->create_supervisor) {
            return;
        }

        $supervisor = Supervisor::create(
            [
                "name" => $component->name,
                "last_name" => $component->last_name ?? "",
                "email" => $component->email,
                "phone" => $component->phone,
                "network_operator_id" => $component->network_operator_id,
                "identification" => $component->identification,

            ]
        );

        $user = User::create([
            "name" => $component->name,
            "last_name" => $component->last_name ?? "",
            "email" => $component->email,
            "phone" => $component->phone,
            "network_operator_id" => $component->network_operator_id,
            "identification" => $component->identification,
            "type" => User::TYPE_SUPERVISOR

        ]);
        $supervisor->update([
            "user_id" => $user->id
        ]);

        ClientSupervisor::create([
            "client_id" => $client->id,
            "supervisor_id" => $supervisor->id,
            "active" => true
        ]);
    }

    private function createBillingInformation(Component $component, Client $client)
    {
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
