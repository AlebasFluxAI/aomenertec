<?php

namespace App\Http\Services\V1\Admin\Equipment;

use App\Http\Livewire\V1\Admin\Equipment\AddEquipment;
use App\Http\Services\Singleton;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EquipmentEditService extends Singleton
{
    public function mount(Component $component, Equipment $equipment)
    {
        $component->fill([
            'equipment' => $equipment,
            'equipmentName' => $equipment->name,
            'equipmentDescription' => $equipment->description,
            'equipmentSerial' => $equipment->serial,
            'equipmentTypeId' => $equipment->equipmentType->id,
            'equipmentTypes' => [],
            'picked' => false,
        ]);
    }

    public function loadEquipmentType(Component $component)
    {
        $component->equipment_types = EquipmentType::paginate();
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->equipment->fill($this->mapper($component));
            $component->equipment->update();
            $component->redirectRoute("administrar.v1.equipos.detalle", ["equipment" => $component->equipment->id]);
        });
    }

    private function mapper(Component $component)
    {
        return [
            "serial" => $component->equipmentSerial,
            "name" => $component->equipmentName,
            "description" => $component->equipmentDescription,
            "equipment_type_id" => $component->equipmentTypeId,
        ];
    }

    public function updatedEquipmentTypeId(Component $component)
    {
        $component->picked = false;
        $component->equipmentTypes = EquipmentType::where('id', 'ilike', "%" . $component->equipmentTypeId . "%")
            ->orWhere('type', 'ilike', "%" . $component->equipmentTypeId . "%")->limit(3)->get();
    }

    public function updatingSearch(Component $component)
    {
        $component->equipment_types = EquipmentType::whereId($component->equipment_type_id)->paginate(15);
    }

    public function setEquipmentType(Component $component, $equipmentType)
    {
        $component->picked = true;
        $equipmentType = json_decode($equipmentType);
        $component->equipmentTypeId = $equipmentType->id;
    }
}
