<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\IndexClientService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;

class IndexClient extends Component
{
    use WithPagination;
    use FilterTrait;

    public $clientType = "ZNI Sistema fotovoltaico";
    public $filterAuxColumn = "client_type_id";
    public $filterAuxValue = null;
    private $indexClientService;

    public function __construct($id = null)
    {
        $this->indexClientService = IndexClientService::getInstance();
        parent::__construct($id);
    }

    public function mount()
    {
        return $this->indexClientService->mount($this);
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

    public function disableClient($clientId)
    {
        $this->indexClientService->disableClient($this, $clientId);

    }

    public function render()
    {
        return view('livewire.v1.admin.client.index-client', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return ($this->indexClientService->getData($this));
    }

    public function setFilter($filterValue)
    {
        return $this->indexClientService->setFilter($this, $filterValue);
    }
}
