<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\Singleton;
use App\Models\Model\V1\Firmware;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class FirmwareEditService extends Singleton
{
    public function mount(Component $component, Firmware $model)
    {
        $component->fill([
            'model' => $model
        ]);
    }


    public function submitForm(Component $component)
    {
        $component->model->update();
        $component->emitTo('livewire-toast', 'show', "Firmware {$component->model->name} actualizado exitosamente");
    }
}
