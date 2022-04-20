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

class AddClientService extends Singleton
{
    public function mount(Component $component)
    {
        $component->fill([
            'serials' => collect([]),
            'equipment' => [],
            'strata' => Stratum::get(), 'client_type_id' => '',
            'client_types' => ClientType::get(),
            'voltage_levels' => VoltageLevel::get(),
            'subsistence_consumptions' => SubsistenceConsumption::get(), 'contribution' => true,
            'location_types' => LocationType::get(), 'locations' => [],
            'departments' => Department::get(),
            'municipalities' => [],
            'equipment_types' => [],
            'network_operator_id' => Auth::user()->networkOperator ? Auth::user()->networkOperator->id : null,
            'picked_network_operator' => false, 'message_network_operator' => 'Digite identificación del operador de red', 'network_operators' => [],
            'picked_aux_network_operator' => false, 'message_aux_network_operator' => 'Digite identificación del operador de red', 'aux_network_operators' => [],
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
        }
    }

    public function updatedNetworkOperator(Component $component)
    {
        $component->picked_network_operator = false;
        $component->message_network_operator = "No hay operador de red registrado con esta identificación";

        if ($component->network_operator != "") {
            $component->network_operators = NetworkOperator::where("identification", "like", '%' . $component->network_operator . "%")
                ->take(3)->get();
        }
    }

    public function assignNetworkOperator(Component $component, $network_operator)
    {
        $obj = json_decode($network_operator);
        $component->network_operator = $obj->identification;
        $component->network_operator_id = $obj->id;
        $component->picked_network_operator = true;
    }

    public function assignNetworkOperatorFirst(Component $component)
    {
        if (!empty($component->network_operator)) {
            $usuario = NetworkOperator::where("identification", "like", '%' . $component->network_operator . "%")
                ->first();
            if ($usuario) {
                $component->network_operator = $usuario->identification;
                $component->network_operator_id = $usuario->id;
            } else {
                $component->network_operator = "...";
            }
            $component->picked_network_operator = true;
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
        $necessary_equipment = count($component->client_type->equipmentTypes);
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
                    $component->serials = Equipment::where([
                        ["serial", "like", '%' . $value . "%"],
                        ["equipment_type_id", $type_id],
                    ])->whereNotIn('assigned', [true])
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
        $component->validate();
        while (true) {
            $code = $this->clientCode();
            if (!(Client::whereCode($code)->exists())) {
                break;
            }
        }
        $client = Client::create([
            'name' => $component->name,
            'email' => $component->email,
            'code' => $code,
            'phone' => $component->phone,
            'identification' => $component->identification,
            'latitude' => $component->latitude,
            'longitude' => $component->longitude,
            'direction' => $component->direction,
            'network_topology' => $component->network_topology,
            'active' => $component->active,
            'contribution' => $component->contribution,
            'public_lighting_tax' => $component->public_lighting_tax ?? true,
            'network_operator_id' => $component->network_operator_id,
            'department_id' => $component->department_id,
            'municipality_id' => $component->municipality_id,
            'location_id' => $component->location_id,
            'client_type_id' => $component->client_type_id,
            'subsistence_consumption_id' => $component->subsistence_consumption_id ?? 1,
            'voltage_level_id' => $component->voltage_level_id,
            'stratum_id' => $component->stratum_id,
        ]);
        foreach ($component->equipment as $item) {
            EquipmentClient::create([
                'client_id' => $client->id,
                'equipment_id' => $item['id'],
                'current_assigned' => true,
            ]);
            Equipment::find($item['id'])->update(['assigned' => true]);
        }
        session()->flash('success', 'Cliente creado con exito');
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
}
