<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminAddEquipmentTypeService;
use App\Models\V1\Admin;
use Livewire\Component;

class AddEquipmentTypeAdmin extends Component
{
    public $model;
    public $type;
    public $typeRelated;
    public $types;
    public $type_id;
    public $equipmentTypeId;
    public $equipmentTypes;


    private $adminAddEquipmentTypeService;

    public function __construct($id = null)
    {
        $this->adminAddEquipmentTypeService = AdminAddEquipmentTypeService::getInstance();
        parent::__construct($id);
    }

    public function mount(Admin $admin)
    {
        $this->adminAddEquipmentTypeService->mount($this, $admin);
    }

    public function submitForm()
    {
        $this->adminAddEquipmentTypeService->submitForm($this);
    }

    public function updatedType()
    {
        $this->adminAddEquipmentTypeService->updatedType($this);
    }

    public function assignType($client)
    {
        $this->adminAddEquipmentTypeService->assignType($this, $client);
    }


    public function delete($id)
    {
        $this->adminAddEquipmentTypeService->delete($this, $id);
    }

    public function pass()
    {
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.add-equipment-type-admin')
            ->extends('layouts.v1.app');
    }
}
