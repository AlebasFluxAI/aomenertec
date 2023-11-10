<?php

namespace App\Http\Livewire\V1\Admin\Client\ClientDisabled;

use App\Http\Services\V1\Admin\ClientDisabled\IndexClientDisabledService;
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
    private $indexClientDisabledService;

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

    public function createActivationWorkOrder($clientId)
    {
        $this->indexClientDisabledService->createActivationWorkOrder($this, $clientId);

    }

    public function createActivationWorkOrderConditional($clientId)
    {
        return $this->indexClientDisabledService->createActivationWorkOrderConditional($this, $clientId);

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
