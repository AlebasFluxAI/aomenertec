<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Traits\ClientServiceTrait;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentClient;
use App\Models\V1\EquipmentType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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
        $component->serials_array = [];

        $component->equipment_types = $model->admin ? EquipmentType::whereIn("id", $model->admin->adminEquipmentTypes()->pluck("equipment_type_id"))->get() : [];
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

            array_push($component->serials_array, collect([]));
        }
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

    public function addInputEquipment(Component $component)
    {
        $component->equipment_types = EquipmentType::whereSerialized(true)->get();
        $equipments_number = count($component->serials_array);
        if (count($component->equipment) + 1 > $equipments_number) {
            ToastEvent::launchToast($component, "show", "error", "No es posible agregar mas equipos");
            return;
        }
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

    public function assignEquipment(Component $component, $id, $aux)
    {
        $equipment = Equipment::find($id);
        $component->equipment[$aux]['serial'] = $equipment->serial;
        $component->equipment[$aux]['id'] = $equipment->id;
        $component->equipment[$aux]['picked'] = true;
        $component->equipment[$aux]['post'] = "";
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
                    $component->serials_array[$id] = Equipment::where([
                        ["serial", "like", '%' . $value . "%"],
                        ["equipment_type_id", $type_id]
                    ])->take(3)->get();
                    if (count($component->serials_array[$id]) == 0) {
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

    public function save(Component $component)
    {
        DB::transaction(function () use ($component) {
            $equipment_errors = $this->linkEquipments($component, $component->client);
            if (!count($equipment_errors)) {
                $component->redirectRoute("v1.admin.client.detail.client", ["client" => $component->client->id]);
            }

            foreach ($equipment_errors as $equipment) {
                ToastEvent::launchToast($component, "show", "error", "Equipo {$equipment->id}  - {$equipment->name} ya esta asignado a otro cliente");
            }
        });
    }

    private function linkEquipments(Component $component, Client $client)
    {
        $error = [];
        foreach ($component->equipment as $item) {
            if (!$item["id"]) {
                continue;
            }
            $equipment = Equipment::find($item['id']);
            if (EquipmentClient::where('equipment_id', $equipment->id)->exists() and !$equipment->has_multiple_connection) {
                $error[] = $equipment;
                continue;
            }

            if (EquipmentClient::where('equipment_id', $equipment->id)->where('client_id', $client->id)->exists()) {
                continue;
            }
            EquipmentClient::create([
                'client_id' => $client->id,
                'equipment_id' => $equipment->id,
                'current_assigned' => true,
            ]);
            $equipment->update(['assigned' => true]);
        }
        return $error;
    }
}
