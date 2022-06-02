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
            "equipments" => $this->getEquipments(),
            "equipmentRelated" => $model->equipments
        ]);
    }

    private function getEquipments()
    {
        return Equipment::getModelAsKeyValue();
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            if (!$component->equipmentId) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Seleccione un tipo de equipo"]);
                return;
            }

            if ($component->model->equipments()->whereId($component->equipmentId)->exists()) {
                return;
            }

            $equipment = Equipment::find($component->equipmentId);
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


            $this->refreshAdminEquipmentType($component);
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Equipo agregado"]);
        });
    }

    private function refreshAdminEquipmentType($component)
    {
        $component->equipmentRelated = $component->model->equipments()->get();
        $component->equipments = $this->getEquipments();
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
}
