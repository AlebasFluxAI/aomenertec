<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\Singleton;
use App\Models\Model\V1\Firmware;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FirmwareAddService extends Singleton
{
    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->validate();
            $firmware = new Firmware($component->model);
            $firmware->save();
            $firmware->saveImageOnModelWithMorphMany($component->file, "evidences");

            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$firmware->name} creado"]);
            $component->redirectRoute("administrar.v1.usuarios.superadmin.firmware.detalles", ["firmware" => $firmware->id]);

        });
    }

    public function updated(Component $component, $propertyName)
    {
        $component->validateOnly($propertyName);
    }
}
