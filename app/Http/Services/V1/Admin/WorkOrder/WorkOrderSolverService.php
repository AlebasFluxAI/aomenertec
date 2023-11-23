<?php

namespace App\Http\Services\V1\Admin\WorkOrder;

use App\Http\Services\Singleton;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\WorkOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class WorkOrderSolverService extends Singleton
{
    use WithPagination;

    public function mount(Component $component, WorkOrder $workOrder)
    {
        $component->fill([
            "model" => $workOrder,
        ]);

        $component->equipment_types = EquipmentType::get();

    }

    public function assignEquipment(Component $component, $id)
    {
        $equipment = Equipment::find($id);
        $component->equipment_id = $equipment->id;
        $component->equipment = $equipment;
        $component->equipment_picked = true;

    }

    public function updatedEquipmentSerial(Component $component, $type_id)
    {
        $component->bachelors_equipments = Equipment::whereEquipmentTypeId($type_id)->get();

    }


    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
        
            foreach ($component->evidences as $evidence) {
                $component->model->saveImageOnModelWithMorphMany($evidence, "evidences");
            }

            $component->model->update([
                "solution_description" => $component->solution_description,
                "status" => WorkOrder::WORK_ORDER_STATUS_CLOSED,
                "execution_time_hours" => $component->execution_time_hours ?? 0,
                "execution_time_minutes" => $component->execution_time_minutes ?? 0,
                "final_recommendations" => $component->final_recommendations

            ]);

            if ($component->equipment) {
                $component->model->equipments()->create(["equipment_id" => $component->equipment->id]);
            }
            if ($component->model->type == WorkOrder::WORK_ORDER_TYPE_DISABLE_CLIENT and $component->model->client) {
                $component->model->client->disableClient();
            }
            if ($component->model->type == WorkOrder::WORK_ORDER_TYPE_ENABLE_CLIENT and $component->model->client) {
                $component->model->client->enableClient();
            }
        });
        $component->redirectRoute("administrar.v1.ordenes_de_servicio.detalle", ["workOrder" => $component->model->id]);
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

    public function updated(Component $component, $property_name, $value)
    {
        if (strpos($property_name, "serial") !== false) {
            if (!$value) {
                $component->bachelors_equipments = [];
                return;
            }
            $component->bachelors_equipments = Equipment::where("serial", "ilike", '%' . $value . '%')->get();
        }
        if (strpos($property_name, "equipment_type_id") !== false) {
            $equipment_type = EquipmentType::find($value);
            $component->equipment_type_id = $equipment_type->id;
            $component->equipment_picked = false;

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
}
