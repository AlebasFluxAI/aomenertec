<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\V1\Equipment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminAddEquipmentService extends Singleton
{
    use EquipmentAssignationTrait;

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
        return $component->model->equipmentTypesAsKeyValue();
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
            $this->refreshEquipmentType($component);
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Equipos agregados"]);
        });
    }

    public function deleteEquipmentAssigned(Component $component, $equipmentId)
    {
        Equipment::whereId($equipmentId)->update([
            "admin_id" => null,
            "has_admin" => false
        ]);
        $component->model->refresh();
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Equipo eliminado"]);
    }
}
