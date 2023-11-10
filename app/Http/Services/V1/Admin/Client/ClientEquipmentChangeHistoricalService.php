<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use Livewire\Component;

class ClientEquipmentChangeHistoricalService extends Singleton
{
    public function mount(Component $component, $client)
    {
        $component->fill([
            "model" => $client
        ]);
    }
}
