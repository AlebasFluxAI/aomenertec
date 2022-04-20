<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\User\Admin\AdminIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\EquipmentType;
use App\Models\V1\SuperAdmin;
use Livewire\Component;
use Livewire\WithPagination;

class IndexAdmin extends Component
{
    use WithPagination;
    use FilterTrait;

    private $indexAdminService;

    public function __construct($id = null)
    {
        $this->indexAdminService = AdminIndexService::getInstance();
        parent::__construct($id);
    }


    public function edit($id)
    {
        $this->indexAdminService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexAdminService->delete($this, $id);
    }

    public function details($id)
    {
        $this->indexAdminService->details($this, $id);
    }

    public function conditionalDelete($id)
    {
        $this->indexAdminService->conditionalDelete($this, $id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.user.admin.index-admin',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexAdminService->getData($this);
    }
}
