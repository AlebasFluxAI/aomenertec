<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\WorkOrderClientService;
use App\Models\V1\Client;
use Livewire\Component;
use Livewire\WithPagination;

class WorkOrderClient extends Component
{
    use WithPagination;

    public $model;

    private $workOrderClientService;

    public function __construct()
    {
        parent::__construct();
        $this->workOrderClientService = WorkOrderClientService::getInstance();
    }

    public function conditionalManuallyCreate($workOrderId)
    {
        return $this->workOrderClientService->conditionalManuallyCreate($this, $workOrderId);
    }

    public function mount(Client $client)
    {
        $this->workOrderClientService->mount($this, $client);
    }

    public function downloadReport($id)
    {
        return $this->workOrderClientService->downloadReport($this, $id);

    }

    public function canDownloadReport($id)
    {
        return $this->workOrderClientService->canDownloadReport($this, $id);

    }


    public function render()
    {
        return view('livewire.v1.admin.client.work-order-client', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }


    public function getData()
    {
        return $this->workOrderClientService->getData($this);
    }
}
