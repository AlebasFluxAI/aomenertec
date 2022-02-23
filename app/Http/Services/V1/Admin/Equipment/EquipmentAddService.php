<?php

namespace App\Http\Services\V1\Admin\Equipment;

use App\Http\Livewire\V1\Admin\Equipment\AddEquipment;
use App\Http\Services\Singleton;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use Livewire\Component;

class EquipmentAddService extends Singleton
{
    public function loadEquipmentType(Component $component)
    {
        $component->equipment_types=EquipmentType::paginate();
    }
    public function submitForm(Component $component)
    {
        $equipment=Equipment::create($this->mapper($component));
        session()->flash('message', 'Equipo '.$equipment->name.' creado con exito.');
        $component->mount();
    }
    public function updatedEquipmentTypeId(Component $component)
    {
        $component->picked = false;
        $component->equipmentTypes=EquipmentType::where('id', 'like', "%".$component->equipmentTypeId."%")->limit(3)->get();
    }

    public function updatingSearch(Component $component)
    {
        $component->equipment_types=EquipmentType::whereId($component->equipment_type_id)->paginate(15);
    }

    public function setEquipmentType(Component $component, $equipmentType)
    {
        $component->picked=true;
        $equipmentType=json_decode($equipmentType);
        $component->equipmentTypeId=$equipmentType->id;
    }

    private function mapper(Component  $component)
    {
        return [
            "serial"=>$component->equipmentSerial,
            "name"=>$component->equipmentName,
            "description"=>$component->equipmentDescription,
            "equipment_type_id"=>$component->equipmentTypeId,
        ];
    }
    public function updatedSelectedState(Component $component, $state)
    {
        if (!is_null($state)) {
            $component->states=[
                ["id"=>"2",
                    "name"=>"Kathe"]
            ];
        }
    }
}
