<?php

namespace App\Http\Livewire\V1\Admin\WorkOrder;

use App\Http\Services\V1\Admin\Client\WorkOrderClientService;
use App\Http\Services\V1\Admin\WorkOrder\WorkOrderDetailsService;
use App\Http\Services\V1\Admin\WorkOrder\WorkOrderSolverService;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\RealTimeListener;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;

class WorkOrderSolver extends Component
{
    use WithFileUploads;

    public $model;
    public $solution_description;
    public $evidences = [];


    private $workOrderSolverService;

    public function __construct()
    {
        parent::__construct();
        $this->workOrderSolverService = WorkOrderSolverService::getInstance();
    }

    public function mount(WorkOrder $workOrder)
    {
        $this->workOrderSolverService->mount($this, $workOrder);
    }

    public function submitForm()
    {
        $this->workOrderSolverService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.workOrder.solver-work-order')
            ->extends('layouts.v1.app');
    }
}
