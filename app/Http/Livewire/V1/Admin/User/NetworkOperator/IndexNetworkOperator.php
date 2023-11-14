<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorIndexService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;

class IndexNetworkOperator extends Component
{
    use WithPagination;
    use FilterTrait;

    private $indexNetworkOperatorService;

    public function __construct($id = null)
    {
        $this->indexNetworkOperatorService = NetworkOperatorIndexService::getInstance();
        parent::__construct($id);
    }


    public function render()
    {
        return view(
            'livewire.v1.admin.user.network-operator.index-network-operator',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexNetworkOperatorService->getData($this);
    }

    public function deleteNetworkOperator($id)
    {
        $this->indexNetworkOperatorService->deleteNetworkOperator($this, $id);
    }

    public function disableNetworkOperator($id)
    {
        $this->indexNetworkOperatorService->disableNetworkOperator($this, $id);
    }

    public function getEnabledNetworkOperator($id)
    {
        return $this->indexNetworkOperatorService->getEnabledNetworkOperator($this, $id);
    }

    public function getEnabledAuxNetworkOperator($id)
    {
        return $this->indexNetworkOperatorService->getEnabledAuxNetworkOperator($this, $id);
    }


    public function conditionalDeleteNetworkOperator($networkOperatorId)
    {
        return $this->indexNetworkOperatorService->conditionalDeleteNetworkOperator($this, $networkOperatorId);
    }

    public function conditionalLinkEquipmentNetworkOperator($networkOperatorId)
    {
        return $this->indexNetworkOperatorService->conditionalLinkEquipmentNetworkOperator($this, $networkOperatorId);
    }
}
