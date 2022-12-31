<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientAlertIndexService;
use App\Http\Services\V1\Admin\Client\IndexClientService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use Livewire\Component;
use Livewire\WithPagination;

class ClientAlertIndex extends Component
{
    use WithPagination;
    use FilterTrait;

    public $model;
    private $clientAlertIndexService;

    public function __construct($id = null)
    {
        $this->clientAlertIndexService = ClientAlertIndexService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        $this->clientAlertIndexService->mount($this, $client);
    }

    public function deleteAlert($alertId)
    {
        $this->clientAlertIndexService->deleteAlert($this, $alertId);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.client-alert-index', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->clientAlertIndexService->getData($this);
    }
}
