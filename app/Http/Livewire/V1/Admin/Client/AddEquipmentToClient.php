<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\AddClientEquipmentService;
use App\Models\Traits\ClientFormTrait;
use App\Models\V1\Client;
use Livewire\Component;

class AddEquipmentToClient extends Component
{
    use ClientFormTrait;

    public $model;
    public $technician;

    private $addClientEquipmentService;


    public function __construct()
    {
        parent::__construct();
        $this->addClientEquipmentService = AddClientEquipmentService::getInstance();
    }

    public function updated($property_name, $value)
    {
        $this->addClientEquipmentService->updated($this, $property_name, $value);
    }

    public function updatedNetworkOperatorId()
    {
        $this->addClientEquipmentService->updatedNetworkOperatorId($this);
    }

    public function updatedLatitude()
    {
        $this->addClientEquipmentService->updatedLatitude($this);
    }

    public function assignEquipment($equipment, $aux)
    {
        $this->addClientEquipmentService->assignEquipment($this, $equipment, $aux);
    }

    public function updatedClientTypeId()
    {
        return $this->addClientEquipmentService->updatedClientTypeId($this);
    }

    public function assignEquipmentFirst($type_id)
    {
        $this->addClientEquipmentService->assignEquipmentFirst($this, $type_id);
    }

    public function mount(Client $client)
    {
        $this->addClientEquipmentService->mount($this, $client);
    }

    public function updatedDepartmentId()
    {
        $this->addClientEquipmentService->updatedDepartmentId($this);
    }

    public function updatedMunicipalityId()
    {
        $this->addClientEquipmentService->updatedMunicipalityId($this);
    }


    public function updatedLocationTypeId()
    {
        $this->addClientEquipmentService->updatedLocationTypeId($this);
    }

    public function updatedNetworkOperator()
    {
        $this->addClientEquipmentService->updatedNetworkOperator($this);
    }

    public function assignNetworkOperator($network_operator)
    {
        $this->addClientEquipmentService->assignNetworkOperator($this, $network_operator);
    }

    public function assignNetworkOperatorFirst()
    {
        $this->addClientEquipmentService->assignNetworkOperatorFirst($this);
    }

    public function addInputEquipment()
    {
        $this->addClientEquipmentService->AddInputEquipment($this);
    }

    public function deleteInputEquipment()
    {
        $this->addClientEquipmentService->deleteInputEquipment($this);
    }

    public function save()
    {
        $this->addClientEquipmentService->save($this);
    }

    public function importClient()
    {
        $this->addClientEquipmentService->importClient($this);
    }

    public function assignTechnician($technician)
    {
        $this->addClientEquipmentService->assignTechnician($this, $technician);
    }

    public function updatedTechnician()
    {
        $this->addClientEquipmentService->updatedTechnician($this);
    }

    public function updatedPersonType()
    {
        $this->addClientEquipmentService->updatedPersonType($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.client.add-equipment-to-client')
            ->extends('layouts.v1.app');
    }
}
