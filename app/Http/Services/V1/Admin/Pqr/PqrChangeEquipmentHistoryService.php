<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\Traits\PqrStatusTrait;
use Livewire\Component;

class PqrChangeEquipmentHistoryService extends Singleton
{
    use PqrStatusTrait;

    public function mount(Component $component, $model)
    {
        $component->model = $model;
    }
}
