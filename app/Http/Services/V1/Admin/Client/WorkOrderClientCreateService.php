<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use Livewire\Component;
use Livewire\WithPagination;

class WorkOrderClientCreateService extends Singleton
{
    use WithPagination;

    public function mount(Component $component, Client $client)
    {
        $component->fill([
            "model" => $client
        ]);
    }

    public function getData(Component $component)
    {
        return $component->model->workOrders->pagination();
    }
}
