<?php

namespace App\Http\Services\V1\Admin\AlertType;

use App\Http\Services\Singleton;
use App\Models\V1\AlertType;
use Livewire\Component;

class AlertTypeDetailService extends Singleton
{
    public function mount(Component $component, AlertType $alertType)
    {
        $component->fill([
            'alertType' => $alertType,
        ]);
    }

    public function edit(Component $component)
    {
        $component->redirectRoute("administrar.v1.equipos.alertas.tipos.editar", ["alertType" => $component->alertType->id]);
    }
}
