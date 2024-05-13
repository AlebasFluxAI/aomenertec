<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientAlertIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
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
            "alerts" => $this->getData(true),
            "events" => $this->getData(false),
        ])->extends('layouts.v1.app');
    }

    public function getData($alert)
    {
        return $this->clientAlertIndexService->getData($this, $alert);
    }
}
