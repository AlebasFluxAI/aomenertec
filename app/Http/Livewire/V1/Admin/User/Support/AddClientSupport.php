<?php

namespace App\Http\Livewire\V1\Admin\User\Support;

use App\Http\Services\V1\Admin\User\Support\SupportAddClientService;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Support;
use Livewire\Component;

class AddClientSupport extends Component
{
    use ValidateUserFormTrait;

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
    public $clientsRelated;
    public $message_client;


    private $addSupportClient;

    public function __construct($id = null)
    {
        $this->addSupportClient = SupportAddClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Support $support)
    {
        $this->addSupportClient->mount($this, $support);
    }

    public function addClient()
    {
        $this->addSupportClient->addClient($this);
    }

    public function updatedClient()
    {
        $this->addSupportClient->updatedClient($this);
    }

    public function assignClient($client)
    {
        $this->addSupportClient->assignClient($this, $client);
    }


    public function delete($client)
    {
        $this->addSupportClient->delete($this, $client);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.support.add-client-support')
            ->extends('layouts.v1.app');
    }
}
