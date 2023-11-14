<?php

namespace App\Http\Livewire\V1\Admin\WorkOrder;

use App\Http\Services\V1\Admin\WorkOrder\WorkOrderIndexService;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function downloadReport($id)
    {
        return $this->workOrderDetailsService->downloadReport($this, $id);

    }

    public function canDownloadReport($id)
    {
        return $this->workOrderDetailsService->canDownloadReport($this, $id);

    }

    public function processEquipmentReplace($workOrderId)
    {
        $this->workOrderDetailsService->processEquipmentReplace($this, $workOrderId);
    }

    public function adminWorkOrderConditional($workOrderId)
    {
        return $this->workOrderDetailsService->adminWorkOrderConditional($this, $workOrderId);
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

    public function conditionalStart($workOrderId)
    {
        return $this->workOrderDetailsService->conditionalStart($this, $workOrderId);
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

    public function conditionalManuallyDetail($workOrderId)
    {
        return $this->workOrderDetailsService->conditionalManuallyDetail($this, $workOrderId);

    }

    public function conditionalManuallyCreate($workOrderId)
    {
        return $this->workOrderDetailsService->conditionalManuallyCreate($this, $workOrderId);
    }

    public function conditionalTypeReading($workOrderId)
    {
        return $this->workOrderDetailsService->conditionalTypeReading($this, $workOrderId);
    }
}
