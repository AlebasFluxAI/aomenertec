<?php

namespace App\Http\Services\V1\Admin\EquipmentType;

use App\Http\Livewire\V1\Admin\Equipment\AddEquipment;
use App\Http\Services\Singleton;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use Illuminate\Database\Eloquent\Model;
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
