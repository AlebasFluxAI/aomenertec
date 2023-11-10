<?php

namespace App\Http\Services\V1\Admin\EquipmentType;

use App\Http\Services\Singleton;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use Livewire\Component;

class EquipmentTypeIndexService extends Singleton
{
    public function delete(Component $component, $dataId)
    {
        EquipmentType::find($dataId)->delete();
        $component->emitTo('livewire-toast', 'show', "Tipo de equipo {$dataId} eliminado exitosamente");

        $component->render();
    }

    public function edit(Component $component, $id)
    {
        $component->redirectRoute("administrar.v1.equipos.tipos.editar", ["equipmentType" => $id]);
    }

    public function details(Component $component, $id)
    {
        $component->redirectRoute("administrar.v1.equipos.tipos.detalle", ["equipmentType" => $id]);
    }

    public function conditionalDelete(Component $component, $id)
    {
        return Equipment::whereEquipmentTypeId($id)->exists();
    }
}
