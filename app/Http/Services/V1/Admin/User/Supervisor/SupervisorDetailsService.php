<?php

namespace App\Http\Services\V1\Admin\User\Supervisor;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\MicrocontrollerData;
use Livewire\Component;

class SupervisorDetailsService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }

    public function delete(Component $component, $clientId)
    {
        Client::find($clientId)->delete();
        $component->emitTo('livewire-toast', 'show', "Equipo {$clientId} eliminado exitosamente");
        $component->reset();
    }

    public function conditionalMonitoring(Component $component, $modelId)
    {
        return !MicrocontrollerData::whereClientId($modelId)->exists();
    }

    public function conditionalDeleteClient(Component $component, $modelId)
    {
        return MicrocontrollerData::whereClientId($modelId)->exists();
    }
}
