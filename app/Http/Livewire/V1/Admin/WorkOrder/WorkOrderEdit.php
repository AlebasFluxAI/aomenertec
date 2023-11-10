<?php

namespace App\Http\Livewire\V1\Admin\WorkOrder;

use App\Http\Services\V1\Admin\WorkOrder\WorkOrderEditService;
use App\Models\V1\WorkOrder;
use Livewire\Component;
use Livewire\WithPagination;

class WorkOrderEdit extends Component
{
    use WithPagination;

    public $model;
    public $types;
    public $type;
    public $description;
    public $technician;
    public $picked_technician;
    public $message_technician;
    public $technicians;
    public $technician_id;
    public $technician_select_disabled;

    private $workOrderEditService;

    public function __construct()
    {
        parent::__construct();
        $this->workOrderEditService = WorkOrderEditService::getInstance();
    }

    public function mount(WorkOrder $workOrder)
    {
        $this->workOrderEditService->mount($this, $workOrder);
    }

    public function submitForm()
    {
        $this->workOrderEditService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.workOrder.edit-work-order')
            ->extends('layouts.v1.app');
    }


}
