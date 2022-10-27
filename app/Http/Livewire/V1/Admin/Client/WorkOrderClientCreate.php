<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\WorkOrderClientService;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\RealTimeListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;

class WorkOrderClientCreate extends Component
{
    use WithPagination;
    use WithFileUploads;

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

    public $supports;
    public $support_id;
    public $support_select_disabled;
    public $photos = [];
    public $tools;
    public $materials;
    public $equipmentsBachelor;
    public $days;
    public $hours;
    public $minutes;
    public $equipment_id;
    private $workOrderClientService;


    public function __construct()
    {
        parent::__construct();
        $this->workOrderClientService = WorkOrderClientService::getInstance();
    }

    public function submitForm()
    {
        $this->workOrderClientService->submitForm($this);
    }

    public function mount(Client $client)
    {
        $this->workOrderClientService->mount($this, $client);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.work-order-client-create', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }


    public function getData()
    {
        return $this->workOrderClientService->getData($this);
    }
}
