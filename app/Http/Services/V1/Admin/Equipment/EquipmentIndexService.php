<?php

namespace App\Http\Services\V1\Admin\Equipment;

use App\Http\Livewire\V1\Admin\Equipment\AddEquipment;
use App\Http\Services\Singleton;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Livewire\Component;

class EquipmentIndexService extends Singleton
{
    public function deleteEquipment(Component $component, $equipmentId)
    {
        Equipment::find($equipmentId)->delete();
        $component->emitTo('livewire-toast', 'show', "Equipo {$equipmentId} eliminado exitosamente");
        $component->reset();
    }

    public function getEquipments()
    {
        return Equipment::with("equipmentType")->paginate(15);
    }

    public function edit(Component $component, $equipmentId)
    {
        $component->redirectRoute("administrar.v1.equipos.editar", ["equipment" => $equipmentId]);
    }

    public function details(Component $component, $equipmentId)
    {
        $component->redirectRoute("administrar.v1.equipos.detalle", ["equipment" => $equipmentId]);
    }

    public function conditionalDelete(Component $component, $id)
    {
        return Equipment::whereId($id)->whereAssigned(true)->exists();
    }

    public function getData(Component $component)
    {
        if ($component->filter) {
            return Equipment::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
        }
        return Equipment::paginate(15);
    }
}
