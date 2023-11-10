<?php

namespace App\Http\Services\V1\Admin\EquipmentAlert;

use App\Http\Services\Singleton;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentAlert;
use App\Models\V1\EquipmentType;
use Livewire\Component;

class EquipmentAlertAddService extends Singleton
{
    public function mount(Component $component)
    {
        foreach (AlertType::get() as $alertType) {
            $component->alertTypes[] = [
                "key" => $alertType->name . " (" . $alertType->unit . ")",
                "value" => $alertType->id,
            ];
        }

        $component->fill([
            'type' => "alert",
            'value' => null,
            'equipments' => [],
            'equipmentId' => null,
            'picked' => [],

        ]);
    }

    public function loadEquipmentType(Component $component)
    {
        $component->equipment_types = EquipmentType::pagination();
    }

    public function submitForm(Component $component)
    {
        $equipment = EquipmentAlert::create($this->mapper($component));
        $component->emitTo('livewire-toast', 'show', 'Alerta para equipo ' . $equipment->equipment->serial . ' creada con exito.');
    }

    private function mapper(Component $component)
    {
        return [
            "value" => $component->value,
            "alert_type_id" => $component->alertType,
            "equipments_id" => $component->equipmentId
        ];
    }

    public function updatingSearch(Component $component)
    {
        $component->equipments = Equipment::whereId($component->equipment_id)->pagination();
    }

    public function updatedEquipmentId(Component $component)
    {
        $component->equipments = Equipment::where('id', 'like', "%" . $component->equipmentId . "%")
            ->orWhere('name', 'like', "%" . $component->equipmentId . "%")
            ->limit(3)->get();
    }


    public function setEquipment(Component $component, $equipment)
    {
        $component->picked = true;
        $equipment = json_decode($equipment);
        $component->equipmentId = $equipment->id;
    }
}
