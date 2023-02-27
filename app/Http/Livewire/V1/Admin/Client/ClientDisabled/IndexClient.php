<?php

namespace App\Http\Livewire\V1\Admin\Client\ClientDisabled;

use App\Http\Services\V1\Admin\Client\IndexClientService;
use App\Http\Services\V1\Admin\ClientDisabled\IndexClientDisabledService;
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


    private $indexClientDisabledService;
    public $clientType = "ZNI Sistema fotovoltaico";
    public $filterAuxColumn = "client_type_id";
    public $filterAuxValue = null;

    public function __construct($id = null)
    {
        $this->indexClientDisabledService = IndexClientDisabledService::getInstance();
        parent::__construct($id);
    }

    public function setFilter($filterValue)
    {
        return $this->indexClientDisabledService->setFilter($this, $filterValue);
    }

    public function getClient()
    {
        return $this->indexClientDisabledService->getClient();
    }

    public function enableClient($clientId)
    {
        return $this->indexClientDisabledService->enableClient($this, $clientId);
    }


    public function render()
    {
        return view('livewire.v1.admin.client.clientDisabled.index-client-disabled', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexClientDisabledService->getData($this);
    }
}
