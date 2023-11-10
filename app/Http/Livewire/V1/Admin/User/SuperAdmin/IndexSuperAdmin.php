<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin;

use App\Http\Services\V1\Admin\User\SuperAdmin\SuperAdminIndexService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;

class IndexSuperAdmin extends Component
{
    use WithPagination;
    use FilterTrait;

    public $model;


    private $indexSuperAdminService;

    public function __construct($id = null)
    {
        $this->indexSuperAdminService = SuperAdminIndexService::getInstance();
        parent::__construct($id);
    }


    public function edit($id)
    {
        $this->indexSuperAdminService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexSuperAdminService->delete($this, $id);
    }

    public function details($id)
    {
        $this->indexSuperAdminService->details($this, $id);
    }

    public function disableSuperAdmin($id)
    {
        $this->indexSuperAdminService->disableSuperAdmin($this, $id);
    }

    public function getEnabledSuperAdmin($id)
    {
        return $this->indexSuperAdminService->getEnabledSuperAdmin($this, $id);
    }

    public function getEnabledAuxSuperAdmin($id)
    {
        return $this->indexSuperAdminService->getEnabledAuxSuperAdmin($this, $id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.user.super.index-super-admin',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexSuperAdminService->getData($this);
    }
}
