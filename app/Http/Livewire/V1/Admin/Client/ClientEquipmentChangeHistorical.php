<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientEquipmentChangeHistoricalService;
use App\Http\Services\V1\Admin\Client\IndexClientService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use Livewire\Component;
use Livewire\WithPagination;

class ClientEquipmentChangeHistorical extends Component
{
    public $model;
    private $clientEquipmentChangeHistoricalService;

    public function __construct($id = null)
    {
        $this->clientEquipmentChangeHistoricalService = ClientEquipmentChangeHistoricalService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        return $this->clientEquipmentChangeHistoricalService->mount($this, $client);
    }


    public function render()
    {
        return view('livewire.v1.admin.client.client-change-equipment-historical-pqr')
            ->extends('layouts.v1.app');
    }


}
