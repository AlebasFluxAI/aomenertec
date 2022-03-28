<?php

namespace App\Http\Services\V1\Admin\AlertType;

use App\Http\Livewire\V1\Admin\Equipment\AddEquipment;
use App\Http\Services\Singleton;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentAlert;
use App\Models\V1\EquipmentType;
use Livewire\Component;

class AlertTypeAddService extends Singleton
{
    public function mount(Component $component)
    {
        $component->fill([
            'name' => '',
            'value' => '',
            "unit" => ''

        ]);
    }


    public function submitForm(Component $component)
    {
        AlertType::create($component->all());
        $component->emitTo('livewire-toast', 'show', 'Tipo de alarma ' . $component->name . ' creada con exito.');
        $component->mount();
    }
}
