<?php

namespace App\Http\Services\V1\Admin\EquipmentAlert;

use App\Http\Services\Singleton;
use App\Models\V1\EquipmentAlert;
use Livewire\Component;

class EquipmentAlertIndexService extends Singleton
{
    public function delete(Component $component, $dataId)
    {
        EquipmentAlert::find($dataId)->delete();
        $component->emitTo('livewire-toast', 'show', "Alerta de equipo {$dataId} eliminada exitosamente");

        $component->render();
    }

    public function edit(Component $component, $id)
    {
        $component->redirectRoute("administrar.v1.equipos.alertas.editar", ["equipmentAlert" => $id]);
    }

    public function details(Component $component, $id)
    {
        $component->redirectRoute("administrar.v1.equipos.alertas.detalle", ["equipmentAlert" => $id]);
    }
}
