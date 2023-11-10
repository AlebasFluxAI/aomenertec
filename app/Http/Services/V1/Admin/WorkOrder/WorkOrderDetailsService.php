<?php

namespace App\Http\Services\V1\Admin\WorkOrder;

use App\Http\Services\Singleton;
use App\Models\V1\WorkOrder;
use Livewire\Component;
use Livewire\WithPagination;

class WorkOrderDetailsService extends Singleton
{
    use WithPagination;

    public function mount(Component $component, WorkOrder $workOrder)
    {
        $component->fill([
            "model" => $workOrder,
        ]);
    }
}
