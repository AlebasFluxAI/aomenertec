<?php

namespace App\Http\Services\V1\Admin\ClientDisabled;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Client;
use App\Scope\ClientEnabledScope;
use Livewire\Component;

class DetailsClientDisabledService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $model = Client::withoutGlobalScope(ClientEnabledScope::class)->find($model);
        $component->fill([
            'client' => $model,
        ]);
        foreach ($model->equipments as $index => $item) {
            $component->equipment[$index] = ["key" => $item->equipmentType->type, "value" => $item->serial . " - " . $item->description];
        }
    }


}
