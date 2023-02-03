<?php

namespace App\Http\Livewire\V1\Admin\User\Support;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\User\Support\IndexWorkOrderService;
use App\Http\Services\V1\Admin\User\Support\SupportIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Support;
use App\Models\V1\EquipmentType;
use App\Models\V1\Admin;
use Livewire\Component;
use Livewire\WithPagination;

class IndexWorkOrder extends Component
{
    use WithPagination;
    use FilterTrait;

    private $indexWorkOrderService;

    public function __construct($id = null)
    {
        $this->indexWorkOrderService = IndexWorkOrderService::getInstance();
        parent::__construct($id);
    }

    public function takeWorkOrder($workOrderId)
    {
        
        return $this->indexWorkOrderService->takeWorkOrder($this, $workOrderId);

    }

    public function render()
    {
        return view('livewire.v1.admin.user.support.index-work-order', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexWorkOrderService->getData($this);
    }
}
