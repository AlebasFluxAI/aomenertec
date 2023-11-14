<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminAddService;
use App\Models\Traits\AddUserTypeTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddAdmin extends Component
{
    use WithFileUploads;
    use AddUserTypeTrait;

    public $decodedAddress;
    public $latitude;
    public $longitude;
    public $form_title;
    public $model;
    public $message;
    public $icon;
    public $styles;
    public $person_types;
    public $identification_types;
    public $indicatives;
    public $indicative;
    protected $rules = [
        'model.identification' => 'required|min:6|unique:users,identification',
        'model.name' => 'required|min:6',
        'model.last_name' => 'required|min:6',
        'model.phone' => 'min:7|unique:users,phone',
        'model.email' => 'required|email|unique:users,email',
        'model.css_file' => 'required',
        'model.address_details' => 'required',
        'model.latitude' => 'required',
        'model.longitude' => 'required',
        'model.billing_name' => 'required',
        'model.billing_address' => 'required',
        'model.person_type' => 'required',
        'model.identification_type' => 'required',
        'model.indicative' => 'required',
    ];
    private $adminAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->adminAddService = AdminAddService::getInstance();
    }

    public function mount()
    {
        $this->adminAddService->mount($this);
    }

    public function updatedModel($value, $key)
    {
        $this->adminAddService->updatedModel($this, $value, $key);
    }

    public function updatedLatitude()
    {
        $this->adminAddService->updatedLatitude($this);
    }


    public function updatedLongitude()
    {
        $this->adminAddService->updatedLongitude($this);
    }

    public function updated($propertyName)
    {
        $this->adminAddService->updated($this, $propertyName);
    }

    public function submitForm()
    {
        $this->adminAddService->submitForm($this);
    }

    public function setStyle()
    {
        $this->adminAddService->setStyle($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.add-admin')
            ->extends('layouts.v1.app');
    }
}
