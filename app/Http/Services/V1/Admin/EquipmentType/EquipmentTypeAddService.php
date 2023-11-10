<?php

namespace App\Http\Services\V1\Admin\EquipmentType;

use App\Http\Services\Singleton;
use App\Models\V1\EquipmentAlert;
use App\Models\V1\EquipmentType;
use Livewire\Component;

class EquipmentTypeAddService extends Singleton
{
    public function mount(Component $component)
    {
        $component->fill([
            'type' => '',
            'description' => '',
        ]);
    }


    public function submitForm(Component $component)
    {
        $equipmentType = EquipmentType::create($this->mapper($component));
        $component->redirectRoute("administrar.v1.equipos.tipos.detalle", ["equipmentType" => $equipmentType->id]);
    }

    private function mapper(Component $component)
    {
        return [
            "type" => $component->type,
            "description" => $component->description
        ];
    }
}
