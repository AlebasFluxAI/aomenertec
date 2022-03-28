<?php

namespace App\Http\Livewire\V1\Admin\User\Supervisor;

use App\Http\Services\V1\Admin\User\Supervisor\SupervisorAddClientService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorAddService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorEditService;
use App\Models\V1\Supervisor;
use Livewire\Component;
use Livewire\WithFileUploads;

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
    private $editSupervisorService;

    public function __construct($id = null)
    {
        $this->editSupervisorService = SupervisorAddClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Supervisor $supervisor)
    {
        $this->editSupervisorService->mount($this, $supervisor);
    }

    public function addClient()
    {
        $this->editSupervisorService->addClient($this);
    }

    public function updatedClient()
    {
        $this->editSupervisorService->updatedClient($this);
    }

    public function assignClient($client)
    {
        $this->editSupervisorService->assignClient($this, $client);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.supervisor.add-client-supervisor')
            ->extends('layouts.v1.app');
    }
}
