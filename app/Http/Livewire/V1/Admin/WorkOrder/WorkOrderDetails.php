<?php

namespace App\Http\Livewire\V1\Admin\WorkOrder;

use App\Http\Services\V1\Admin\Client\WorkOrderClientService;
use App\Http\Services\V1\Admin\WorkOrder\WorkOrderDetailsService;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\RealTimeListener;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;

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
