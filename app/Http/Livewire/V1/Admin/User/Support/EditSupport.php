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

    protected $rules = [
        'model.name' => 'required|min:6',
        'model.last_name' => 'required|min:6',
        'model.address_details' => 'required',
        'model.latitude' => 'required',
        'model.longitude' => 'required',
        'model.billing_name' => 'required',
        'model.billing_address' => 'required',
        'model.person_type' => 'required',
        'model.identification_type' => 'required',
    ];

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
