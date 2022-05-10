<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\EquipmentType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminAddEquipmentTypeService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->model = $model;
        $component->fill([
            "equipmentTypes" => $this->getTypes(),
            "typeRelated" => $model->adminEquipmentTypes
        ]);
    }

    private function getTypes()
    {
        return EquipmentType::getModelAsKeyValue();
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            if (!$component->equipmentTypeId) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Seleccione un tipo de equipo"]);
                return;
            }

            if ($component->model->adminEquipmentTypes()->whereEquipmentTypeId($component->equipmentTypeId)->exists()) {
                return;
            }
            $component->model->adminEquipmentTypes()->create([
                "equipment_type_id" => $component->equipmentTypeId
            ]);

            $this->refreshAdminEquipmentType($component);
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Tipo de equipo agregado"]);
        });
    }

    private function refreshAdminEquipmentType($component)
    {
        $component->typeRelated = $component->model->adminEquipmentTypes()->get();
    }

    public function delete(Component $component, $adminEquipmentId)
    {
        AdminEquipmentType::whereId($adminEquipmentId)->delete();
        $this->refreshAdminEquipmentType($component);
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Tipo de equipo eliminado"]);
    }

    public function assignType(Component $component)
    {
    }
}
