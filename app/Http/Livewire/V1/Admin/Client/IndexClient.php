<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\IndexClientService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use Livewire\Component;
use Livewire\WithPagination;

class IndexClient extends Component
{
    use WithPagination;
    use FilterTrait;


    private $indexClientService;

    public function __construct($id = null)
    {
        $this->indexClientService = IndexClientService::getInstance();
        parent::__construct($id);
    }

    public function getClient()
    {
        return $this->indexClientService->getClient();
    }


    public function conditionalMonitoring($id)
    {
        return $this->indexClientService->conditionalMonitoring($this, $id);
    }

    public function conditionalDeleteClient($id)
    {
   
        return $this->indexClientService->conditionalDeleteClient($this, $id);
    }

    public function deleteClient($id)
    {
        $this->indexClientService->deleteClient($this, $id);
    }


    public function render()
    {
        return view('livewire.v1.admin.client.index-client', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexClientService->getData($this);
    }
}
