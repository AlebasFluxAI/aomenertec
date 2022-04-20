<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\User\Admin\AdminIndexService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorIndexService;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use Livewire\Component;
use Livewire\WithPagination;

class IndexNetworkOperator extends Component
{
    use WithPagination;
    use ValidateUserFormTrait;

    private $indexNetworkOperatorService;

    public function __construct($id = null)
    {
        $this->indexNetworkOperatorService = NetworkOperatorIndexService::getInstance();
        parent::__construct($id);
    }


    public function edit($id)
    {
        $this->indexNetworkOperatorService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexNetworkOperatorService->delete($this, $id);
    }

    public function details($id)
    {
        $this->indexNetworkOperatorService->details($this, $id);
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

    public function deleteNetworkOperator($networkOperatorId)
    {
        $operatorName = NetworkOperator::find($networkOperatorId)->name;
        $this->indexNetworkOperatorService->deleteNetworkOperator($networkOperatorId);
        $this->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$operatorName} eliminado"]);

    }

    public function conditionalDelete($networkOperatorId)
    {
        return $this->indexNetworkOperatorService->conditionalDelete($networkOperatorId);
    }
}
