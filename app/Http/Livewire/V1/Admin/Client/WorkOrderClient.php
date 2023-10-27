<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\WorkOrderClientService;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\RealTimeListener;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;

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
