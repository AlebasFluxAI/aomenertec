<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AddEquipmentTypeService;
use App\Models\V1\Admin;
use Livewire\Component;

class AddEquipmentTypeAdmin extends Component
{
    public $model;
    public $type;
    public $typeRelated;
    public $types;
    public $type_id;


    private $addEquipmentTypeService;

    public function __construct($id = null)
    {
        $this->addEquipmentTypeService = AddEquipmentTypeService::getInstance();
        parent::__construct($id);
    }

    public function mount(Admin $admin)
    {
        $this->addEquipmentTypeService->mount($this, $admin);
    }

    public function addType()
    {
        $this->addEquipmentTypeService->addType($this);
    }

    public function updatedType()
    {
        $this->addEquipmentTypeService->updatedType($this);
    }

    public function assignType($client)
    {
        $this->addEquipmentTypeService->assignType($this, $client);
    }


    public function delete($client)
    {
        $this->addEquipmentTypeService->delete($this, $client["id"]);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.add-equipment-type-admin')
            ->extends('layouts.v1.app');
    }
}
