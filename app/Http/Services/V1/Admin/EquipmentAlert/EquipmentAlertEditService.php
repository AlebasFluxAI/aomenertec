<?php

namespace App\Http\Services\V1\Admin\EquipmentAlert;

use App\Http\Services\Singleton;
use App\Models\V1\AlertType;
use App\Models\V1\EquipmentType;
use Livewire\Component;

class EquipmentAlertEditService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->model = $model;
        $component->fill([
            'value' => $model->value,
            'alertType' => $model->alertType->id,
            'equipmentId' => $model->equipment->id,
            'alertTypes' => [[
                "key" => $model->alertType->name . " (" . $model->alertType->unit . ")",
                "value" => $model->alertType->id,
            ]],
        ]);
    }

    public function loadEquipmentType(Component $component)
    {
        $component->equipment_types = EquipmentType::pagination();
    }

    public function submitForm(Component $component)
    {
        $component->model->fill($this->mapper($component));
        $component->model->update();
        $component->emitTo('livewire-toast', 'show', "Equipo {$component->model->name} editado exitosamente");
        $component->redirectRoute("administrar.v1.equipos.alertas.detalle", ["equipmentAlert" => $component->model->id]);
    }

    private function mapper(Component $component)
    {
        return [
            "value" => $component->value,
            "alert_type_id" => $component->alertType,
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
        $component->equipment_types = EquipmentType::whereId($component->equipment_type_id)->pagination();
    }

    public function setEquipmentType(Component $component, $equipmentType)
    {
        $component->picked = true;
        $equipmentType = json_decode($equipmentType);
        $component->equipmentTypeId = $equipmentType->id;
    }


    public function refreshAlertTypes(Component $component)
    {
        $component->alertTypes = [];
        foreach (AlertType::get() as $alertType) {
            $component->alertTypes[] = [
                "key" => $alertType->name . " (" . $alertType->unit . ")",
                "value" => $alertType->id,
            ];
        }
    }
}
