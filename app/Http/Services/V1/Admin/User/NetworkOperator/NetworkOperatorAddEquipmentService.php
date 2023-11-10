<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\V1\Equipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NetworkOperatorAddEquipmentService extends Singleton
{
    use EquipmentAssignationTrait;

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
                    "network_operator_id" => $component->model->id,
                ]);
            }
            $this->refreshEquipmentType($component);
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Equipos agregados"]);
        });
    }

    public function deleteEquipmentAssigned(Component $component, $equipmentId)
    {
        Equipment::whereId($equipmentId)->update([
            "network_operator_id" => null
        ]);
        $component->model->refresh();
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Equipo eliminado"]);
    }

    private function getEquipmentTypes()
    {
        if ($admin = Auth::user()->getAdmin()) {
            if ($admin->getTable() == "super_admins") {
                return $admin->equipmentTypesAsKeyValue();
            }
            return $admin->equipmentTypesAsKeyValue();
        }
        return [];
    }
}
