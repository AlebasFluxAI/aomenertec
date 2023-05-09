<?php

namespace App\Http\Livewire\V1\Admin\User\Support;

use App\Http\Services\V1\Admin\User\Support\IndexPqrService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPqr extends Component
{
    use WithPagination;
    use FilterTrait;

    private $indexWorkOrderService;

    public function __construct($id = null)
    {
        $this->indexWorkOrderService = IndexPqrService::getInstance();
        parent::__construct($id);
    }

    public function takePqr($workOrderId)
    {

        return $this->indexWorkOrderService->takePqr($this, $workOrderId);

    }

    public function render()
    {
        return view('livewire.v1.admin.user.support.index-pqr', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexWorkOrderService->getData($this);
    }
}
