<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\Singleton;
use App\Models\Model\V1\Firmware;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class FirmwareIndexService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.superadmin.firmware.editar", ["firmware" => $modelId]);
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.superadmin.firmware.detalles", ["firmware" => $modelId]);
    }


    public function getData(Component $component)
    {
        if ($component->filter) {
            return Firmware::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }
        return Firmware::pagination();
    }
}
