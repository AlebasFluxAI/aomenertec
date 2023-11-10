<?php

namespace App\Http\Livewire\V1\Admin\WorkOrder;

use App\Http\Services\V1\Admin\WorkOrder\WorkOrderDetailsService;
use App\Models\V1\WorkOrder;
use Livewire\Component;
use Livewire\WithPagination;

class WorkOrderDetails extends Component
{
    use WithPagination;

    public $model;

    private $workOrderClientService;

    public function __construct()
    {
        parent::__construct();
        $this->workOrderClientService = WorkOrderDetailsService::getInstance();
    }

    public function mount(WorkOrder $workOrder)
    {
        $this->workOrderClientService->mount($this, $workOrder);
    }

    public function render()
    {
        return view('livewire.v1.admin.workOrder.details-work-order')
            ->extends('layouts.v1.app');
    }
}
