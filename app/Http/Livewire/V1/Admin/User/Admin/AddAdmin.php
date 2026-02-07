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
        'latitude' => 'required',
        'longitude' => 'required',
        'model.billing_name' => 'required',
        'model.billing_address' => 'required',
        'model.person_type' => 'required',
        'model.identification_type' => 'required',
        'model.indicative' => 'required',
    ];

    protected $messages = [
        'model.identification.required' => 'El número de identificación es obligatorio.',
        'model.identification.min' => 'El número de identificación debe tener al menos 6 caracteres.',
        'model.identification.unique' => 'Este número de identificación ya está registrado.',
        'model.name.required' => 'El nombre es obligatorio.',
        'model.name.min' => 'El nombre debe tener al menos 6 caracteres.',
        'model.last_name.required' => 'El apellido es obligatorio.',
        'model.last_name.min' => 'El apellido debe tener al menos 6 caracteres.',
        'model.phone.min' => 'El teléfono debe tener al menos 7 dígitos.',
        'model.phone.unique' => 'Este número de teléfono ya está registrado.',
        'model.email.required' => 'El correo electrónico es obligatorio.',
        'model.email.email' => 'Ingrese un correo electrónico válido.',
        'model.email.unique' => 'Este correo electrónico ya está registrado.',
        'model.css_file.required' => 'Debe seleccionar un estilo de personalización.',
        'model.address_details.required' => 'Los detalles de dirección son obligatorios.',
        'latitude.required' => 'La latitud es obligatoria. Seleccione una ubicación en el mapa.',
        'longitude.required' => 'La longitud es obligatoria. Seleccione una ubicación en el mapa.',
        'model.billing_name.required' => 'El nombre para facturación es obligatorio.',
        'model.billing_address.required' => 'La dirección de facturación es obligatoria.',
        'model.person_type.required' => 'Debe seleccionar el tipo de persona.',
        'model.identification_type.required' => 'Debe seleccionar el tipo de identificación.',
        'model.indicative.required' => 'Debe seleccionar el indicativo telefónico.',
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
