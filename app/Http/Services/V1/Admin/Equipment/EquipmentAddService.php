<?php

namespace App\Http\Services\V1\Admin\Equipment;

use App\Http\Services\Singleton;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EquipmentAddService extends Singleton
{
    public function mount(Component $component)
    {
        $component->fill([
            'equipmentName' => null,
            'equipmentDescription' => null,
            'equipmentSerial' => null,
            'equipmentTypeId' => null,
            'equipmentTypes' => $this->getEquipmentTypes(),
            'picked' => false,
        ]);
    }

    public function getEquipmentTypes()
    {
        $user = Auth::user();
        if ($user->superAdmin) {
            return EquipmentType::getModelAsKeyValue();
        }

        $userModel = User::getUserModel();
        return $userModel->equipmentTypesAsKeyValue();
    }

    public function loadEquipmentType(Component $component)
    {
        $component->equipment_types = EquipmentType::pagination();
    }

    public function submitForm(Component $component)
    {

        $component->validate();
        $equipment = Equipment::create($this->mapper($component));
        $component->redirectRoute("administrar.v1.equipos.detalle", ["equipment" => $equipment->id]);
    }

    private function mapper(Component $component)
    {
        return [
            "serial" => $component->equipmentSerial,
            "name" => $component->name,
            "description" => $component->equipmentDescription,
            "equipment_type_id" => $component->equipmentTypeId,
            "has_multiple_connection" => $component->has_multiple_connection ?? false
        ];
    }

    public function updatedEquipmentTypeId(Component $component)
    {
        //TODO
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

    public function updatedSelectedState(Component $component, $state)
    {
    }
}
