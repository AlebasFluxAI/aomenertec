<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Livewire\V1\Admin\User\AssignedEquipmentInterface;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorAddEquipmentService;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\TableRowCheckTrait;
use App\Models\V1\NetworkOperator;
use Livewire\Component;

class AddEquipmentNetworkOperator extends Component implements AssignedEquipmentInterface
{
    use EquipmentAssignationTrait;
    use TableRowCheckTrait;

    public $assignationType = NetworkOperator::class;

    public $model;
    public $equipmentRelated;


    private $networkOperatorAddEquipmentService;

    public function __construct($id = null)
    {
        $this->networkOperatorAddEquipmentService = NetworkOperatorAddEquipmentService::getInstance();
        parent::__construct($id);
    }

    public function getPageTitle()
    {
        return "Equipos de operador de red";
    }

    public function mount(NetworkOperator $networkOperator)
    {
        $this->networkOperatorAddEquipmentService->mount($this, $networkOperator);
    }

    public function submitForm()
    {
        $this->networkOperatorAddEquipmentService->submitForm($this);
    }


    public function assignType($client)
    {
        $this->networkOperatorAddEquipmentService->assignType($this, $client);
    }


    public function pass()
    {
    }

    public function render()
    {
        return view('livewire.v1.admin.user.add-equipment-generic')
            ->extends('layouts.v1.app');
    }

    public function getNavOptions()
    {
        return [
            ["button_align" => "right",
                "click_action" => "",
                "button_icon" => "fas fa-list",
                "button_content" => "Ver listado",
                "target_route" => "administrar.v1.usuarios.operadores.listado",
            ],
        ];
    }


    public function deleteEquipmentAssigned($id)
    {
        $this->networkOperatorAddEquipmentService->deleteEquipmentAssigned($this, $id);
    }

    public function updatedEquipmentTypeId()
    {
        $this->networkOperatorAddEquipmentService->updatedEquipmentTypeId($this);
    }

    public function updatedEquipmentFilter()
    {
        $this->networkOperatorAddEquipmentService->updatedEquipmentFilter($this);
    }

    public function updatedSelectedAll()
    {
        $this->networkOperatorAddEquipmentService->updatedSelectedAll($this);
    }

    public function assignEquipment($equipment)
    {
        $this->networkOperatorAddEquipmentService->assignEquipment($this, $equipment);
    }

    public function getCardTitle()
    {
        return "Operador de red";
    }
}
