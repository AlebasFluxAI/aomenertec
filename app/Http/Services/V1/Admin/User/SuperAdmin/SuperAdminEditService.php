<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin;

use App\Http\Services\Singleton;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class SuperAdminEditService extends Singleton
{
    public function mount(Component $component, SuperAdmin $model)
    {
        $component->fill([
            'model' => $model
        ]);
    }


    public function submitForm(Component $component)
    {
        $component->model->update();
        $component->emitTo('livewire-toast', 'show', "Super administrador {$component->model->name} actualizado exitosamente");
    }
}
