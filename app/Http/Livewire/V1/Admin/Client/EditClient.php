<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\EditClientService;
use App\Models\Traits\ClientFormTrait;
use App\Models\V1\Client;
use Livewire\Component;

class EditClient extends Component
{
    use ClientFormTrait;

    public $model;
    protected $rules = [
        'identification' => 'required|min:6|unique:clients,identification',
        'name' => 'required|min:8',
        'phone' => 'min:7',
        'email' => 'email|unique:clients,email',
        'network_operator' => 'required|min:2',
    ];
    private $editClientService;

    public function __construct($id = null)
    {
        $this->editClientService = EditClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        $this->editClientService->mount($this, $client);
    }

    public function updatedNetworkOperatorId()
    {
        $this->editClientService->updatedNetworkOperatorId($this);
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
