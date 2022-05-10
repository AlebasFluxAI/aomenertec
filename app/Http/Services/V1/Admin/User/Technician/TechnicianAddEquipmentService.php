<?php

namespace App\Http\Services\V1\Admin\User\Technician;

use App\Http\Services\Singleton;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperatorEquipmentType;
use App\Models\V1\TechnicianEquipmentType;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TechnicianAddEquipmentService extends Singleton
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
        if ($networkOperator = User::getUserModel()) {
            return $networkOperator->networkOperatorEquipmentToTechnicianAsKeyValue();
        }
        return [];
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            if (!$component->equipmentId) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Seleccione un tipo de equipo"]);
                return;
            }
            if ($component->model->equipments()->whereId($component->equipmentId)->exists()) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Este tipo ya fue asignado"]);

                return;
            }

            Equipment::whereId($component->equipmentId)
                ->update([
                    "technician_id" => $component->model->id
                ]);

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
        Equipment::whereId($equipmentId)
            ->update([
                "technician_id" => null
            ]);

        $this->refreshAdminEquipmentType($component);
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Equipo eliminado"]);

        $this->refreshAdminEquipmentType($component);
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Equipo eliminado"]);
    }

    public function assignType(Component $component)
    {
    }
}
