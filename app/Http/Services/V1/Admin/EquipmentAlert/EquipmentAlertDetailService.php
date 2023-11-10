<?php

namespace App\Http\Services\V1\Admin\EquipmentAlert;

use App\Http\Services\Singleton;
use App\Models\V1\EquipmentAlert;
use Livewire\Component;

class EquipmentAlertDetailService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->model = $model;
    }

    public function edit(Component $component)
    {
        $component->redirectRoute("administrar.v1.equipos.alertas.editar", ["equipmentAlert" => $component->model->id]);
    }
}
