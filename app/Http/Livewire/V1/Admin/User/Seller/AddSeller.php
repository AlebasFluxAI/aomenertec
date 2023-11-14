<?php

namespace App\Http\Livewire\V1\Admin\User\Seller;

use App\Http\Services\V1\Admin\User\Seller\SellerAddService;
use Livewire\Component;

class AddSeller extends Component
{
    public $decodedAddress;
    public $latitude;
    public $longitude;
    public $form_title;
    public $model;
    public $message;
    public $person_types;
    public $identification_types;
    public $admins;
    public $admin_id;
    public $network_operators;
    public $indicatives;
    public $indicative;


    protected $rules = [
        'model.identification' => 'required|min:6|unique:users,identification',
        'model.name' => 'required|min:6',
        'model.last_name' => 'required|min:6',
        'model.phone' => 'min:7|unique:users,phone',
        'model.email' => 'required|email|unique:users,email',
        'model.address_details' => 'required',
        'model.latitude' => 'required',
        'model.longitude' => 'required',
        'model.billing_name' => 'required',
        'model.billing_address' => 'required',
        'model.person_type' => 'required',
        'model.identification_type' => 'required',
        'model.network_operator_id' => 'required',
    ];


    private $sellerAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->sellerAddService = SellerAddService::getInstance();
    }

    public function mount()
    {
        $this->sellerAddService->mount($this);
    }

    public function updatedModel($value, $key)
    {
        $this->sellerAddService->updatedModel($this, $value, $key);
    }

    public function updated($propertyName)
    {
        $this->sellerAddService->updated($this, $propertyName);
    }

    public function updatedLatitude()
    {
        $this->sellerAddService->updatedLatitude($this);
    }

    public function updatedLongitude()
    {
        $this->sellerAddService->updatedLongitude($this);
    }

    public function updatedAdminId()
    {
        $this->sellerAddService->updatedAdminId($this);
    }

    public function submitForm()
    {
        $this->sellerAddService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.seller.add-seller')
            ->extends('layouts.v1.app');
    }
}
