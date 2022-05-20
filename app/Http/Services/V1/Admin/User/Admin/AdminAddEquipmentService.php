<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminAddEquipmentService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->model = $model;
        $component->fill([
            "equipments" => [],
            "equipmentRelated" => $model->equipments,
            "equipmentTypes" => $this->getEquipmentTypes($component),
            "empty_text" => "Utilice los filtros para ver el listado de equipos",
            "equipmentPicked" => false,
            "equipmentId" => null,
            "equipmentBachelors" => [],
            "selectedRows" => [],
            "equipmentFilter" => null,
            "equipmentTypeId" => null
        ]);
    }

    private function getEquipmentTypes(Component $component)
    {
        return $component->model->adminEquipmentTypesAsKeyValue();
    }

    public function removeEquipment(Component $component, $equipmentId)
    {
        $this->adminAddEquipmentService->removeEquipment($this, $equipmentId);


    }

    public function updatedSelectedAll(Component $component)
    {
        if (!$component->selectedAll) {
            $component->selectedRows = [];
        } else {
            $component->selectedRows = is_array($component->equipmentBachelors) ? [] : $component->equipmentBachelors->pluck("id");
        }
    }

    public function assignEquipment(Component $component, $equipment)
    {
        $obj = json_decode($equipment);
        $component->equipment = $obj->serial . " - " . $obj->name;
        $component->equipment_id = $obj->id;
        $component->equipmentPicked = true;
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            if (count($component->selectedRows) == 0) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Seleccione un tipo de equipo"]);
                return;
            }
            foreach ($component->selectedRows as $equipmentId) {
                if ($component->model->equipments()->whereId($equipmentId)->exists()) {
                    return;
                }

                $equipment = Equipment::find($equipmentId);
                $equipment->update([
                    "admin_id" => $component->model->id,
                ]);

                if ($component->model->adminEquipmentTypes()
                    ->whereEquipmentTypeId($equipment->equipmentType->id)
                    ->doesntExist()) {
                    $component->model->adminEquipmentTypes()->create([
                        "equipment_type_id" => $equipment->equipmentType->id,
                    ]);
                }


            }
            $this->refreshAdminEquipmentType($component);
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Equipos agregados"]);

        });
    }

    private function refreshAdminEquipmentType($component)
    {
        $this->setEquipmentBachelors($component);
        $component->selectedRows = [];
    }

    private function setEquipmentBachelors(Component $component)
    {
        if (!$component->equipmentTypeId) {
            return;
        }
        $component->equipmentBachelors = Equipment::whereEquipmentTypeId($component->equipmentTypeId)
            ->where(function ($query) use ($component) {
                if ($component->equipmentFilter) {
                    return $query->
                    where("serial", "like", '%' . $component->equipmentFilter . "%")
                        ->orWhere("name", "like", '%' . $component->equipmentFilter . "%");
                }
            })
            ->whereNotIn("id", $component->model->refresh()->equipments->pluck("id"))
            ->orderBy("id", "desc")
            ->get();
    }

    public function updatedEquipmentTypeId(Component $component)
    {
        $this->setEquipmentBachelors($component);
    }

    public function delete(Component $component, $equipmentId)
    {
        Equipment::whereId($equipmentId)->update([
            "admin_id" => null
        ]);
        $this->refreshAdminEquipmentType($component);
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Equipo eliminado"]);
    }

    public function updated(Component $component)
    {
    }

    public function updatedEquipmentFilter(Component $component)
    {
        $this->setEquipmentBachelors($component);
    }

    private function getEquipments()
    {
        return Equipment::getModelAsKeyValue();
    }
}
