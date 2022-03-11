<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Livewire\V1\Admin\Client\AddClient;
use App\Http\Services\Singleton;
use App\Models\V1\ClientEquipment;
use App\Models\V1\ClientType;
use App\Models\V1\Consumer;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentCondition;
use App\Models\V1\EquipmentType;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\NetworkTopology;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
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
            'strata' => Stratum::get(), 'client_type_id' => '',
            'client_types' => ClientType::get(),
            'voltage_levels' => VoltageLevel::get(),
            'subsistence_consumptions' => SubsistenceConsumption::get(), 'contribution' => true,
            'location_types' => LocationType::get(), 'locations' => [],
            'departments' => Department::get(),
            'municipalities' => [],
            'picked_network_operator' => false, 'message_network_operator' => 'Digite identificación del operador de red', 'network_operators' => [],
            'picked_aux_network_operator' => false, 'message_aux_network_operator' => 'Digite identificación del operador de red', 'aux_network_operators' => [],
            ]);
    }

    public function updatedLocationTypeId(Component $component)
    {
        $component->location_id = "";
        if ($component->municipality_id != ""){
            $component->locations = Location::whereMunicipalityId($component->municipality_id)
                ->whereLocationTypeId($component->location_type_id)
                ->get();
        } else{
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
        if ($component->location_type_id != ""){
            $component->locations = Location::whereMunicipalityId($component->municipality_id)
                ->whereLocationTypeId($component->location_type_id)
                ->get();
        } else{
            $component->locations = [];
        }
    }

    public function updatedClientTypeId(Component $component)
    {
        if ($component->client_type_id != "") {
            $component->client_type = ClientType::find($component->client_type_id);
            $component->equipment_types = $component->client_type->equipmentTypes;
            foreach ($component->equipment_types as $type) {
                $component->equipment[$type->id] = "";
                $component->equipment_id[$type->id] = "";
                $component->pickeds[$type->id] = false;
                $component->posts[$type->id] = "Digite serial de " . $type->name;
                $component->serials = collect([]);
            }
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
        $component->network_operator_id = $obj->id;
        $component->picked_network_operator= true;
    }
    public function assingnNetworkOperatorFirst(Component $component)
    {
        if (!empty($component->network_operator)) {
            $usuario = User::role('network_operator')->where("identification", "like", '%' . $component->network_operator . "%")
                ->first();
            if ($usuario) {
                $component->network_operator = $usuario->identification;
                $component->network_operator_id= $usuario->id;
            } else {
                $component->network_operator = "...";
            }
            $component->picked_network_operator = true;
        }
    }

    public function updatedAuxNetworkOperator(Component $component)
    {
        $component->picked_aux_network_operator = false;
        $component->message_aux_network_operator = "No hay operador de red registrado con esta identificación";

        if ($component->aux_network_operator != "") {
            $component->aux_network_operators = User::role('network_operator')->where("identification", "like", '%' . $component->aux_network_operator . "%")
                ->take(3)->get();
        }
    }
    public function assignAuxNetworkOperator(Component $component, $network_operator)
    {
        $obj = json_decode($network_operator);
        $component->aux_network_operator = $obj->identification;
        $component->aux_network_operator_id = $obj->id;
        $component->picked_aux_network_operator= true;
    }
    public function assingnAuxNetworkOperatorFirst(Component $component)
    {
        if (!empty($component->aux_network_operator)) {
            $usuario = User::role('network_operator')->where("identification", "like", '%' . $component->aux_network_operator . "%")
                ->first();
            if ($usuario) {
                $component->aux_network_operator = $usuario->identification;
                $component->aux_network_operator_id= $usuario->id;
            } else {
                $component->aux_network_operator = "...";
            }
            $component->picked_aux_network_operator = true;
        }
    }


    public function updated(Component $component, $property_name, $value){

        if (strpos($property_name, "equipment") !== false){
            $id = filter_var($property_name, FILTER_SANITIZE_NUMBER_INT);
            $equipment_type = EquipmentType::find($id);
            $component->pickeds[$id] = false;
            foreach ($component->equipment_types as $type) {
                $component->posts[$type->id] = "";
            }
            if (strlen($value) >= 2){
                $component->serials = Equipment::where([
                    ["serial", "like", '%' . $value . "%"],
                    ["equipment_condition_id", 1],
                    ["equipment_type_id", $id ],
                ])->whereNotIn('assigned', [true])
                    ->take(3)->get();
                if (count($component->serials)<=0){
                    $component->posts[$id] = "No existe ".$equipment_type->name;
                }
            }
        }

    }
    public function assignEquipment(Component $component, $id){
        $equipment = Equipment::find($id);
        $component->equipment[$equipment->equipment_type_id] = $equipment->serial;
        $component->equipment_id[$equipment->equipment_type_id] = $equipment->id;
        $component->pickeds[$equipment->equipment_type_id] = true;
        foreach ($component->equipment_types as $type) {
            $component->posts[$type->id] = "Digite serial de " . $type->name;
        }
        $component->posts[$equipment->equipment_type_id] = "";
    }
    public function assignEquipmentFirst(Component $component, $id){
        if (strlen($component->equipment[$id]) >= 2) {
            $equipment_type = EquipmentType::find($id);
            $equipment = Equipment::where([
                ["serial", "like", '%' . $component->equipment[$equipment_type->id]. "%"],
                ["equipment_condition_id", 1],
                ["equipment_type_id",$equipment_type->id ],
            ])->whereNotIn('assigned', [true])
                ->first();
            if ($equipment) {
                $component->equipment[$equipment_type->id] = $equipment->serial;
                $component->equipment_id[$equipment_type->id] = $equipment->id;
                $component->posts[$equipment_type->id] = "";
            } else {
                $component->solar_panel = "...";
            }
            $component->pickeds[$equipment_type->id] = true;
        }
    }

    public function userCode($input ='0123456789', $strength = 10) {
        $input_length = strlen($input);
        $random_codigo = "";
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_codigo .= $random_character;
        }
        return $random_codigo;
    }
    public function save(Component $component){
        while (true){
            $code = $this->generate_codigo();
            if (!(Client::whereCodigo($code)->exists())){
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
            'public_lighting_tax' => $component->public_lighting_tax,
            'network_operator_id' => $component->network_operator_id,
            'department_id' => $component->department_id,
            'municipality_id' => $component->municipality_id,
            'location_id' => $component->location_id,
            'client_type_id' => $component->client_type_id,
            'subsistence_consumption_id' => $component->subsistence_consumption,
            'voltage_level_id' => $component->voltage_level,
            'stratum_id' => $component->stratum,
        ]);
        foreach ($component->equipment_id as $equipment){
            ClientEquipment::create([
                'client_id' => $client->id,
                'equipment_id' => $equipment->id,
                'active' => true,
            ]);
            Equipment::whereEquipmentId($equipment->id)->update(['assigned' => true]);
        }
    }

}
