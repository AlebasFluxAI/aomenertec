<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\AddClientService;
use Livewire\Component;

class AddClient extends Component
{
    public $aux_network_operator_id;
    public $aux_network_operator;
    public $message_aux_network_operator;
    public $picked_aux_network_operator;
    public $aux_network_operators;
    public $message;
    public $file;
    public $identification;
    public $name;
    public $phone;
    public $location_type_id;
    public $location_types;
    public $location_id;
    public $locations;
    public $direction;
    public $email;
    public $latitude;
    public $longitude;
    public $stratum_id;
    public $strata;
    public $decodedAddress;
    public $client_type;
    public $client_type_id;
    public $client_types;
    public $voltage_level_id;
    public $voltage_levels;
    public $subsistence_consumption_id;
    public $subsistence_consumptions;
    public $contribution;
    public $public_lighting_tax;
    public $active;
    public $network_topology;
    public $network_operator_id;
    public $network_operator;
    public $picked_network_operator;
    public $network_operators;
    public $message_network_operator;
    public $equipment;
    public $serials;
    public $pickeds;
    public $posts;
    public $equipment_id;
    public $equipment_types;
    public $person_types;
    public $person_type;
    public $identification_type;
    public $identification_types;
    public $technician;
    public $picked_technician;
    public $message_technician;
    public $technicians;
    public $create_supervisor;
    public $network_topologies;
    public $last_name;
    public $has_telemetry;
    public $billing_name;
    public $billing_address;
    public $technician_id;
    public $technician_select_disabled;
    public $addressDetails;

    protected $rules = [
        'identification' => 'required|min:6|unique:users,identification|unique:clients,identification',
        'name' => 'required|min:8',
        'phone' => 'min:7',
        'email' => 'email|unique:users,email',
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
