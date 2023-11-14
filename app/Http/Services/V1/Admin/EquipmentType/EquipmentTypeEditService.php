<?php

namespace App\Http\Services\V1\Admin\EquipmentType;

use App\Http\Services\Singleton;
use App\Models\V1\EquipmentAlert;
use Livewire\Component;

class EquipmentTypeEditService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->model = $model;
        $component->fill([
            'type' => $model->type,
            'description' => $model->description,
        ]);
    }


    public function submitForm(Component $component)
    {
        $component->model->fill($this->mapper($component));
        $component->model->update();
        $component->emitTo('livewire-toast', 'show', 'Tipo de equipo ' . $component->type . ' editado con exito.');
        $component->redirectRoute("administrar.v1.equipos.tipos.detalle", ["equipmentType" => $component->model->id]);
    }

    private function mapper(Component $component)
    {
        return [
            'type' => $component->type,
            'description' => $component->description,
        ];
    }
}
