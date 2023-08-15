<?php

namespace App\Http\Livewire\V1\Admin\WorkOrder;

use App\Http\Services\V1\Admin\Client\WorkOrderClientService;
use App\Http\Services\V1\Admin\WorkOrder\WorkOrderDetailsService;
use App\Http\Services\V1\Admin\WorkOrder\WorkOrderIndexService;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\RealTimeListener;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;

class WorkOrderIndex extends Component
{
    use WithPagination;

    public $model;

    private $workOrderDetailsService;

    public function __construct()
    {
        parent::__construct();
        $this->workOrderDetailsService = WorkOrderIndexService::getInstance();
    }

    public function processEquipmentReplace($workOrderId)
    {
        $this->workOrderDetailsService->processEquipmentReplace($this, $workOrderId);
    }

    public function adminWorkOrderConditional($workOrderId)
    {
        return !($this->workOrderDetailsService->setInProgressWorkOrderConditional($this, $workOrderId));
    }

    public function setInProgressWorkOrderConditional($workOrderId)
    {
        return $this->workOrderDetailsService->setInProgressWorkOrderConditional($this, $workOrderId);
    }

    public function setOpenWorkOrderConditional($workOrderId)
    {
        return $this->workOrderDetailsService->setOpenWorkOrderConditional($this, $workOrderId);
    }


    public function replaceEquipmentHandlerConditional($workOrderId)
    {
        return $this->workOrderDetailsService->replaceEquipmentHandlerConditional($this, $workOrderId);
    }

    public function setOpen($workOrderId)
    {
        $this->workOrderDetailsService->setOpen($workOrderId);
    }


    public function setInProgress($workOrderId)
    {
        $this->workOrderDetailsService->setInProgress($workOrderId);
    }

    public function render()
    {
        return view('livewire.v1.admin.workOrder.index-work-order', [
            "data" => $this->getData()
        ])
            ->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->workOrderDetailsService->getData($this);
    }
}
