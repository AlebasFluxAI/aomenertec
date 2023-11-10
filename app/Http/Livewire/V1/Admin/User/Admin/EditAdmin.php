<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminEditService;
use App\Models\V1\Admin;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditAdmin extends Component
{
    use WithFileUploads;

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
        'model.indicative' => 'required',
        'model.latitude' => 'required',
        'model.longitude' => 'required',
        'model.billing_name' => 'required',
        'model.billing_address' => 'required',
        'model.person_type' => 'required',
        'model.identification_type' => 'required',
    ];
    private $editAdminService;

    public function __construct($id = null)
    {
        $this->editAdminService = AdminEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(Admin $admin)
    {
        $this->editAdminService->mount($this, $admin);
    }

    public function updatedModel($value, $key)
    {
        $this->editAdminService->updatedModel($this, $value, $key);
    }

    public function updatedLatitude()
    {
        $this->editAdminService->updatedLatitude($this);
    }

    public function updatedLongitude()
    {
        $this->editAdminService->updatedLongitude($this);
    }

    public function updated($propertyName)
    {
        $this->editAdminService->updated($this, $propertyName);
    }

    public function submitForm()
    {
        $this->editAdminService->submitForm($this);
    }

    public function setStyle()
    {
        $this->editAdminService->setStyle($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.edit-admin')
            ->extends('layouts.v1.app');
    }
}
