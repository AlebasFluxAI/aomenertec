<?php

namespace App\Http\Services\V1\Admin\EquipmentAlert;

use App\Http\Livewire\V1\Admin\Equipment\AddEquipment;
use App\Http\Services\Singleton;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentAlert;
use App\Models\V1\EquipmentType;
use Livewire\Component;

class EquipmentAlertAddService extends Singleton
{
    public function loadEquipmentType(Component $component)
    {
        $component->equipment_types = EquipmentType::paginate();
    }

    public function submitForm(Component $component)
    {

        $equipment = EquipmentAlert::create($this->mapper($component));
        session()->flash('message', 'Equipo ' . $equipment->name . ' creado con exito.');
        $component->mount();
    }

    private function mapper(Component $component)
    {
        return [
            "interval" => $component->interval,
            "equipments_id" => $component->equipmentId,
        ];
    }

    public function updatingSearch(Component $component)
    {
        $component->equipments = Equipment::whereId($component->equipment_id)->paginate(15);
    }

    public function updatedEquipmentId(Component $component)
    {
        $component->picked = false;
        $component->equipments = Equipment::where('id', 'like', "%" . $component->equipmentId . "%")->limit(3)->get();

    }


    public function setEquipment(Component $component, $equipment)
    {
        $component->picked = true;
        $equipment = json_decode($equipment);
        $component->equipmentId = $equipment->id;
    }

    public function updatedSelectedState(Component $component, $state)
    {
        if (!is_null($state)) {
            $component->states = [
                ["id" => "2",
                    "name" => "Kathe"]
            ];
        }
    }
}
