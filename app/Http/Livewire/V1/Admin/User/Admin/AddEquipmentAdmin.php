<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminAddEquipmentService;
use App\Models\V1\Admin;
use Livewire\Component;

class AddEquipmentAdmin extends Component
{
    public $model;
    public $equipment;
    public $equipmentRelated;
    public $equipment_id;
    public $equipmentId;
    public $equipments;


    private $adminAddEquipmentService;

    public function __construct($id = null)
    {
        $this->adminAddEquipmentService = AdminAddEquipmentService::getInstance();
        parent::__construct($id);
    }

    public function mount(Admin $admin)
    {
        $this->adminAddEquipmentService->mount($this, $admin);
    }

    public function submitForm()
    {
        $this->adminAddEquipmentService->submitForm($this);
    }

    public function updated()
    {
        $this->adminAddEquipmentService->updated($this);
    }

    public function assign($client)
    {
        $this->adminAddEquipmentService->assign($this, $client);
    }


    public function delete($id)
    {
        $this->adminAddEquipmentService->delete($this, $id);
    }

    public function pass()
    {
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.add-equipment-admin')
            ->extends('layouts.v1.app');
    }
}
