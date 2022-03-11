<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\AddClientService;
use Livewire\Component;


class AddClient extends Component
{

    public $aux_network_operator_id, $aux_network_operator, $message_aux_network_operator, $picked_aux_network_operator, $aux_network_operators;
    public $message;
    public $file;
    public $identification;
    public $name;
    public $phone;
    public $location_type_id, $location_types;
    public $department_id, $departments;
    public $municipality_id, $municipalities;
    public $location_id, $locations;
    public $direction;
    public $email;
    public $latitude, $longitude;
    public $stratum_id, $strata;
    public $client_type, $client_type_id, $client_types;
    public $voltage_level_id, $voltage_levels;
    public $subsistence_consumption_id, $subsistence_consumptions;
    public $contribution, $public_lighting_tax, $active, $network_topology;
    public $network_operator_id, $network_operator, $picked_network_operator, $network_operators, $message_network_operator;
    public $equipment, $serials;
    public $pickeds, $posts, $equipment_id;
    private $addClientService;

    protected $rules = [
        'identification' => 'required|min:6|unique:users,identification',
        'name' => 'required|min:8',
        'phone' => 'min:7',
        'email' => 'email|unique:users,email',
        'network_operator' => 'required|min:2',
        'aux_network_operator' => 'required|min:2',
        'equipment.*' => 'required|min:2',
        'pickeds.*' => 'required',
    ];

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
    public function assignEquipmentFirst($type_id){
        $this->addClientService->assignEquipmentFirst($this, $type_id);
    }

    public function mount()
    {
        $this->addClientService->mount($this);
    }

    public function updatedDepartment()
    {
        $this->addClientService->updatedDepartment($this);
    }
    public function updatedClientTypeId()
    {
        $this->addClientService->updatedClientTypeId($this);
    }

    public function updatedMunicipality()
    {
        $this->addClientService->updatedMunicipality($this);
    }


    public function updatedLocationType()
    {
        $this->addClientService->updatedLocationType($this);
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

    public function updatedAuxNetworkOperator()
    {
        $this->addClientService->updatedAuxNetworkOperator($this);
    }
    public function assignAuxNetworkOperator($network_operator)
    {
        $this->addClientService->assignAuxNetworkOperator($this, $network_operator);
    }
    public function assignAuxNetworkOperatorFirst()
    {
        $this->addClientService->assignAuxNetworkOperatorFirst($this);
    }

    public function updatedCabinet()
    {
        $this->addClientService->updatedCabinet($this);
    }
    public function assignEquipment($equipment)
    {
        $this->addClientService->assignEquipment($this, $equipment);
    }
    public function assignCabinetFirst()
    {
        $this->addClientService->assignCabinetFirst($this);
    }

    public function updatedSeal()
    {
        $this->addClientService->updatedSeal($this);
    }
    public function assignSeal($equipment)
    {
        $this->addClientService->assignSeal($this, $equipment);
    }
    public function assignSealFirst()
    {
        $this->addClientService->assignSealFirst($this);
    }

    public function updatedMeter()
    {
        $this->addClientService->updatedMeter($this);
    }
    public function assignMeter($equipment)
    {
        $this->addClientService->assignMeter($this, $equipment);
    }
    public function assignMeterFirst()
    {
        $this->addClientService->assignMeterFirst($this);
    }

    public function updatedCard()
    {
        $this->addClientService->updatedCard($this);
    }
    public function assignCard($equipment)
    {
        $this->addClientService->assignCard($this, $equipment);
    }
    public function assignCardFirst()
    {
        $this->addClientService->assignCardFirst($this);
    }

    public function updatedController()
    {
        $this->addClientService->updatedController($this);
    }
    public function assignController($equipment)
    {
        $this->addClientService->assignController($this, $equipment);
    }
    public function assignControllerFirst()
    {
        $this->addClientService->assignControllerFirst($this);
    }

    public function updatedInverter()
    {
        $this->addClientService->updatedInverter($this);
    }
    public function assignInverter($equipment)
    {
        $this->addClientService->assignInverter($this, $equipment);
    }
    public function assignInverterFirst()
    {
        $this->addClientService->assignInverterFirst($this);
    }

    public function updatedBattery()
    {
        $this->addClientService->updatedBattery($this);
    }
    public function assignBattery($equipment)
    {
        $this->addClientService->assignBattery($this, $equipment);
    }
    public function assignBatteryFirst()
    {
        $this->addClientService->assignBatteryFirst($this);
    }

    public function updatedContactor()
    {
        $this->addClientService->updatedContactor($this);
    }
    public function assignContactor($equipment)
    {
        $this->addClientService->assignContactor($this, $equipment);
    }
    public function assignContactorFirst()
    {
        $this->addClientService->assignContactorFirst($this);
    }

    public function updatedSolarPanel()
    {
        $this->addClientService->updatedSolarPanel($this);
    }
    public function assignSolarPanel($equipment)
    {
        $this->addClientService->assignSolarPanel($this, $equipment);
    }
    public function assignSolarPanelFirst()
    {
        $this->addClientService->assignSolarPanelFirst($this);
    }

    public function save()
    {
        $this->addClientService->save($this);
    }
    public function importClient()
    {
        $this->addClientService->importClient($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.add-client')
            ->extends('layouts.v1.app');
    }
}
