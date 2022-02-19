<?php

namespace App\Http\Services\V1\Admin\Equipment;

use App\Http\Livewire\V1\Admin\Equipment\AddEquipment;
use App\Http\Services\Singleton;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use Livewire\Component;

class EquipmentAddService extends Singleton
{
    public function loadEquipmentType(AddEquipment $component)
    {
        $component->equipment_types=EquipmentType::get();
    }
    public function submitForm(AddEquipment $component)
    {
        Equipment::create($component->all());
    }
    public function updatedSelectedState(AddEquipment $component, $state)
    {
        if (!is_null($state)) {
            $component->states=[
                ["id"=>"2",
                    "name"=>"Kathe"]
            ];
        }
    }
}
