<?php

namespace App\Http\Livewire\V1\Admin\User\Supervisor;

use App\Http\Services\V1\Admin\User\Supervisor\SupervisorAddClientService;
use App\Models\V1\Supervisor;
use Livewire\Component;

class AddClientSupervisor extends Component
{
    public $model;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $identification;
    public $clients;
    public $client;
    public $client_picked;
    public $client_id;
    public $message_client;
    public $clientsRelated;

    private $addClientSupervisorService;

    public function __construct($id = null)
    {
        $this->addClientSupervisorService = SupervisorAddClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Supervisor $supervisor)
    {
        $this->addClientSupervisorService->mount($this, $supervisor);
    }

    public function addClient()
    {
        $this->addClientSupervisorService->addClient($this);
    }

    public function updatedClient()
    {
        $this->addClientSupervisorService->updatedClient($this);
    }

    public function assignClient($client)
    {
        $this->addClientSupervisorService->assignClient($this, $client);
    }


    public function delete($client)
    {
        $this->addClientSupervisorService->delete($this, $client["id"]);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.supervisor.add-client-supervisor')
            ->extends('layouts.v1.app');
    }
}
