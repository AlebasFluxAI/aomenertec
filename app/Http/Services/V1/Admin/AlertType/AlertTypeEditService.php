<?php

namespace App\Http\Services\V1\Admin\AlertType;

use App\Http\Services\Singleton;
use App\Models\V1\AlertType;
use App\Models\V1\EquipmentAlert;
use Livewire\Component;

class AlertTypeEditService extends Singleton
{
    public function mount(Component $component, AlertType $alertType)
    {
        $component->alertType = $alertType;
        $component->fill([
            'name' => $alertType->name,
            'value' => $alertType->value,
            "unit" => $alertType->unit

        ]);
    }


    public function submitForm(Component $component)
    {
        $component->alertType->fill($this->mapper($component));
        $component->alertType->update();
        $component->emitTo('livewire-toast', 'show', 'Tipo de alarma ' . $component->name . ' eidtada con exito.');
        $component->redirectRoute("administrar.v1.equipos.alertas.tipos.detalle", ["alertType" => $component->alertType->id]);
    }

    private function mapper(Component $component)
    {
        return [
            "name" => $component->name,
            "value" => $component->value,
            "unit" => $component->unit,
        ];
    }
}
