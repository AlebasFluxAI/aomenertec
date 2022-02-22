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
