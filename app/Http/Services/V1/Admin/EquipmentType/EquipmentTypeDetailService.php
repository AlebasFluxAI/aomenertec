<?php

namespace App\Http\Services\V1\Admin\EquipmentType;

use App\Http\Services\Singleton;
use Livewire\Component;

class EquipmentTypeDetailService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }

    public function edit(Component $component)
    {
        $component->redirectRoute("administrar.v1.equipos.tipos.editar", ["equipmentType" => $component->model->id]);
    }
}
