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

class AddClientEquipmentService extends Singleton
{
    use ClientServiceTrait;

    public function mount(Component $component, Client $model)
    {
        $component->fill([
            'client' => $model,
            'serials' => collect([]),
            'technician' => $model->technician()->first() ? $model->technician()->first()->technician : null
        ]);
        $component->equipment = [];
        $component->equipment_types = $model->clientType->equipmentTypes;
        foreach ($model->clientType->equipmentTypes as $index => $type) {
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
                    if (!$component->technician->id) {
                        ToastEvent::launchToast($component, "show", "error", "Debes seleccionar un tecnico", ["duration" => "2s"]);
                        return;
                    }
                    $component->serials = Equipment::where([
                        ["serial", "like", '%' . $value . "%"],
                    ])->take(3)->get();
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

    public function save(Component $component)
    {
        DB::transaction(function () use ($component) {
            $this->linkEquipments($component, $component->client);
            $component->redirectRoute("v1.admin.client.detail.client", ["client" => $component->client->id]);
        });
    }

}
