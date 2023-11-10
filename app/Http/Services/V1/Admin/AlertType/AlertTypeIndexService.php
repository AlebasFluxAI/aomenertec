<?php

namespace App\Http\Services\V1\Admin\AlertType;

use App\Http\Services\Singleton;
use App\Models\V1\AlertType;
use Livewire\Component;

class AlertTypeIndexService extends Singleton
{
    public function delete(Component $component, $dataId)
    {
        AlertType::find($dataId)->delete();
        $component->emitTo('livewire-toast', 'show', "Equipo {$dataId} eliminado exitosamente");

        $component->render();
    }

    public function edit(Component $component, $id)
    {
        $component->redirectRoute("administrar.v1.equipos.alertas.tipos.editar", ["alertType" => $id]);
    }

    public function details(Component $component, $id)
    {
        $component->redirectRoute("administrar.v1.equipos.alertas.tipos.detalle", ["alertType" => $id]);
    }
}
