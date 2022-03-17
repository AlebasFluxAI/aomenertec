<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\User\Admin\AdminIndexService;
use App\Models\V1\Admin;
use App\Models\V1\EquipmentType;
use App\Models\V1\SuperAdmin;
use Livewire\Component;
use Livewire\WithPagination;

class IndexAdmin extends Component
{
    use WithPagination;


    private $indexEquipmentService;

    public function __construct($id = null)
    {
        $this->indexEquipmentService = AdminIndexService::getInstance();
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

    public function render()
    {
        return view('livewire.v1.admin.user.admin.index-admin',
            [
                "data" => Admin::paginate(15)
            ])->extends('layouts.v1.app');
    }


}
