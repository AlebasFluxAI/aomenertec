<?php

namespace App\Http\Services\V1\Admin\EquipmentType;

use App\Http\Livewire\V1\Admin\Equipment\AddEquipment;
use App\Http\Services\Singleton;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;
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
        EquipmentType::create($this->mapper($component));
        $component->emitTo('livewire-toast', 'show', 'Tipo de equipo ' . $component->type . ' creada con exito.');
        $component->reset();
    }

    private function mapper(Component $component)
    {
        return [
            "type" => $component->type,
            "description" => $component->description
        ];
    }


}
