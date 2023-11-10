<?php

namespace App\Http\Livewire\V1\Admin\User\Support;

use App\Http\Services\V1\Admin\User\Support\SupportIndexService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;

class IndexSupport extends Component
{
    use WithPagination;
    use FilterTrait;

    private $indexEquipmentService;

    public function __construct($id = null)
    {
        $this->indexEquipmentService = SupportIndexService::getInstance();
        parent::__construct($id);
    }


    public function edit($id)
    {
        $this->indexEquipmentService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexEquipmentService->delete($this, $id);
    }

    public function details($id)
    {
        $this->indexEquipmentService->details($this, $id);
    }

    public function addClients($id)
    {
        $this->indexEquipmentService->addClients($this, $id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.user.support.index-support',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexEquipmentService->getData($this);
    }

    public function supportPqrEnabled($support)
    {
        return !($this->indexEquipmentService->supportPqrDisabled($this, $support));
    }

    public function supportPqrDisabled($support)
    {
        return $this->indexEquipmentService->supportPqrDisabled($this, $support);
    }

    public function disablePqrSupport($support)
    {
        return $this->indexEquipmentService->enablePqrSupport($this, $support);
    }

    public function enablePqrSupport($support)
    {
        return $this->indexEquipmentService->enablePqrSupport($this, $support);
    }
}
