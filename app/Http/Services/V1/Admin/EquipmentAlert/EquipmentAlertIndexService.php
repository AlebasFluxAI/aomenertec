<?php

namespace App\Http\Services\V1\Admin\EquipmentAlert;

use App\Http\Livewire\V1\Admin\Equipment\AddEquipment;
use App\Http\Services\Singleton;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use Livewire\Component;

class EquipmentAlertIndexService extends Singleton
{
    public function getEquipments()
    {
        return Equipment::with("equipment_type")->paginate(15);
    }

    public function deleteEquipment(Component $component, $equipmentId)
    {
        Equipment::find($equipmentId)->delete();

        $component->render();
    }

    public function editEquipment(Component $component, $equipmentId)
    {
        $component->redirectRoute("administrar.v1.equipos.editar", ["equipment" => $equipmentId]);
    }
}
