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
    public $decodedAddress;
    public $latitude;
    public $longitude;
    public $form_title;
    public $model;
    public $message;
    public $person_types;
    public $identification_types;
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
