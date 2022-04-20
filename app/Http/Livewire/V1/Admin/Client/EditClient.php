<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\EditClientService;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use Livewire\Component;

class EditClient extends Component
{
    public $client;
    public $identification;
    public $name;
    public $email;
    public $phone;
    public $direction;
    public $latitude;
    public $longitude;
    public $contribution;
    public $public_lighting_tax;
    public $active_client;
    public $network_operator_id;
    public $network_operator;
    public $network_operators;
    public $picked_network_operator;
    public $message_network_operator;
    public $departments;
    public $department_id;
    public $municipalities;
    public $municipality_id;
    public $location_types;
    public $location_id;
    public $location_type_id;
    public $locations;
    public $subsistence_consumptions;
    public $subsistence_consumption_id;
    public $voltage_levels;
    public $voltage_level_id;
    public $strata;
    public $stratum_id;
    public $topologies;
    public $network_topology;
    private $editClientService;

    protected $rules = [
        'identification' => 'required|min:6|unique:clients,identification',
        'name' => 'required|min:8',
        'phone' => 'min:7',
        'email' => 'email|unique:clients,email',
        'network_operator' => 'required|min:2',
    ];

    public function __construct($id = null)
    {
        $this->editClientService = EditClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        $this->editClientService->mount($this, $client);
    }

    public function updatedDepartmentId()
    {
        $this->editClientService->updatedDepartmentId($this);
    }

    public function updatedMunicipality()
    {
        $this->editClientService->updatedMunicipality($this);
    }


    public function updatedLocationTypeId()
    {
        $this->editClientService->updatedLocationTypeId($this);
    }

    public function updatedNetworkOperator()
    {
        $this->editClientService->updatedNetworkOperator($this);
    }
    public function assignNetworkOperator($network_operator)
    {
        $this->editClientService->assignNetworkOperator($this, $network_operator);
    }

    public function submitForm()
    {
        $this->editClientService->submitForm($this);
    }
    public function render()
    {
        return view('livewire.v1.admin.client.edit-client')
            ->extends('layouts.v1.app');
    }
}
