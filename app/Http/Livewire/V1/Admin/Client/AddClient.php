<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\AddClientService;
use App\Models\Traits\ClientFormTrait;
use Livewire\Component;

class AddClient extends Component
{
    use ClientFormTrait;

    public $model;
    protected $rules = [
        'identification' => 'required|min:6',
        'name' => 'required|min:2',
        'network_operator' => 'required|min:2',
        'aux_network_operator' => 'required|min:2',
        'equipment.*.id' => 'required|min:2',
        'equipment.*.index' => 'required|min:2',
        'equipment.*.type_id' => 'required',
        'equipment.*.type' => 'required|min:2',
        'equipment.*.serial' => 'required|min:2',
        'equipment.*.picked' => 'required',
        'equipment.*.post' => 'required|min:2',
        'equipment.*.disable' => 'required|min:2',
    ];
    private $addClientService;


    public function __construct()
    {
        parent::__construct();
        $this->addClientService = AddClientService::getInstance();
    }

    public function updated($property_name, $value)
    {
        if ($this->validateOnly($property_name)) {
            $this->addClientService->updated($this, $property_name, $value);
        }
    }

    public function updatedNetworkOperatorId()
    {
        $this->addClientService->updatedNetworkOperatorId($this);
    }

    public function updatedLatitude()
    {
        $this->addClientService->updatedLatitude($this);
    }

    public function assignEquipment($equipment, $aux)
    {
        $this->addClientService->assignEquipment($this, $equipment, $aux);
    }

    public function updatedClientTypeId()
    {
        return $this->addClientService->updatedClientTypeId($this);
    }

    public function assignEquipmentFirst($type_id)
    {
        $this->addClientService->assignEquipmentFirst($this, $type_id);
    }

    public function mount()
    {
        $this->addClientService->mount($this);
    }

    public function updatedDepartmentId()
    {
        $this->addClientService->updatedDepartmentId($this);
    }

    public function updatedMunicipalityId()
    {
        $this->addClientService->updatedMunicipalityId($this);
    }


    public function updatedLocationTypeId()
    {
        $this->addClientService->updatedLocationTypeId($this);
    }

    public function updatedNetworkOperator()
    {
        $this->addClientService->updatedNetworkOperator($this);
    }

    public function assignNetworkOperator($network_operator)
    {
        $this->addClientService->assignNetworkOperator($this, $network_operator);
    }

    public function assignNetworkOperatorFirst()
    {
        $this->addClientService->assignNetworkOperatorFirst($this);
    }

    public function addInputEquipment()
    {
        $this->addClientService->AddInputEquipment($this);
    }

    public function deleteInputEquipment()
    {
        $this->addClientService->deleteInputEquipment($this);
    }

    public function save()
    {
        $this->addClientService->save($this);
    }

    public function importClient()
    {
        $this->addClientService->importClient($this);
    }

    public function assignTechnician($technician)
    {
        $this->addClientService->assignTechnician($this, $technician);
    }

    public function updatedTechnician()
    {
        $this->addClientService->updatedTechnician($this);
    }

    public function updatedPersonType()
    {
        $this->addClientService->updatedPersonType($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.add-client')
            ->extends('layouts.v1.app');
    }
}
