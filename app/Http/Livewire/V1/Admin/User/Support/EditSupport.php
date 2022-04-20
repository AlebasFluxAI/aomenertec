<?php

namespace App\Http\Livewire\V1\Admin\User\Support;

use App\Http\Services\V1\Admin\User\Support\SupportEditService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorEditService;
use App\Models\V1\Support;
use App\Models\V1\NetworkOperator;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditSupport extends Component
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
    public $message_client;
    private $editSupportService;

    public function __construct($id = null)
    {
        $this->editSupportService = SupportEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(Support $support)
    {
        $this->editSupportService->mount($this, $support);
    }

    public function submitForm()
    {
        $this->editSupportService->submitForm($this);
    }

    public function updatedClient()
    {
        $this->editSupportService->updatedClient($this);
    }

    public function assignClient($client)
    {
        $this->editSupportService->assignClient($this, $client);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.support.edit-support')
            ->extends('layouts.v1.app');
    }
}
