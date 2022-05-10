<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorAddEquipmentService;
use App\Models\V1\NetworkOperator;
use Livewire\Component;

class AddEquipmentNetworkOperator extends Component
{
    public $model;
    public $equipment;
    public $equipmentRelated;
    public $equipments;
    public $equipment_id;
    public $equipmentId;


    private $networkOperatorAddEquipmentService;

    public function __construct($id = null)
    {
        $this->networkOperatorAddEquipmentService = NetworkOperatorAddEquipmentService::getInstance();
        parent::__construct($id);
    }

    public function mount(NetworkOperator $networkOperator)
    {
        $this->networkOperatorAddEquipmentService->mount($this, $networkOperator);
    }

    public function submitForm()
    {
        $this->networkOperatorAddEquipmentService->submitForm($this);
    }

    public function updatedType()
    {
        $this->networkOperatorAddEquipmentService->updatedType($this);
    }

    public function assignType($client)
    {
        $this->networkOperatorAddEquipmentService->assignType($this, $client);
    }


    public function delete($id)
    {
        $this->networkOperatorAddEquipmentService->delete($this, $id);
    }

    public function pass()
    {
    }

    public function render()
    {
        return view('livewire.v1.admin.user.network-operator.add-equipment-network-operator')
            ->extends('layouts.v1.app');
    }
}
