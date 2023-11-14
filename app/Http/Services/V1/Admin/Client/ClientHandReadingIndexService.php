<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use Livewire\Component;

class ClientHandReadingIndexService extends Singleton
{

    public function mount(Component $component, $client)
    {
        $component->model = $client;
        $component->client = $client;
    }

    public function getData(Component $component)
    {
        return $component->model->handReading;
    }
}
