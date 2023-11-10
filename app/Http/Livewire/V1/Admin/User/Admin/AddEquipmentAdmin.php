<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminAddEquipmentService;
use App\Models\Traits\TableRowCheckTrait;
use App\Models\V1\Admin;
use Livewire\Component;

class AddEquipmentAdmin extends Component
{
    use TableRowCheckTrait;

    public $model;
    public $equipment;
    public $equipmentRelated;
    public $equipment_id;
    public $equipmentId;
    public $equipments;
    public $equipmentTypes;
    public $equipmentTypeId;
    public $empty_text;
    public $equipmentPicked;
    public $equipmentFilter;
    public $equipmentBachelors;
    public $assignationType = Admin::class;
    private $adminAddEquipmentService;

    public function __construct($id = null)
    {
        $this->adminAddEquipmentService = AdminAddEquipmentService::getInstance();
        parent::__construct($id);
    }

    public function assignEquipment($equipment)
    {
        $this->adminAddEquipmentService->assignEquipment($this, $equipment);
    }

    public function mount(Admin $admin)
    {
        $this->adminAddEquipmentService->mount($this, $admin);
    }

    public function submitForm()
    {
        $this->adminAddEquipmentService->submitForm($this);
    }

    public function deleteEquipmentAssigned($id)
    {
        $this->adminAddEquipmentService->deleteEquipmentAssigned($this, $id);
    }

    public function pass()
    {
    }

    public function updatedEquipmentTypeId()
    {
        $this->adminAddEquipmentService->updatedEquipmentTypeId($this);
    }

    public function updatedEquipmentFilter()
    {
        $this->adminAddEquipmentService->updatedEquipmentFilter($this);
    }

    public function updatedSelectedAll()
    {
        $this->adminAddEquipmentService->updatedSelectedAll($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.add-equipment-admin')
            ->extends('layouts.v1.app');
    }
}
