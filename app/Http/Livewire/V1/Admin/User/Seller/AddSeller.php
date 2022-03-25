<?php

namespace App\Http\Livewire\V1\Admin\User\Seller;


use App\Http\Services\V1\Admin\User\Seller\SellerAddService;
use Livewire\Component;

class AddSeller extends Component
{
    public $password;
    public $identification;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $message;
    public $picked;
    public $networkOperators;
    public $network_operator_id;


    protected $rules = [
        'identification' => 'required|min:6|unique:users,identification',
        'name' => 'required|min:8',
        'phone' => 'min:7',
        'email' => 'required|email|unique:users,email',
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

    public function submitForm()
    {
        $this->sellerAddService->submitForm($this);
    }

    public function updatedNetworkOperatorId()
    {
        $this->sellerAddService->updatedNetworkOperatorId($this);
    }


    public function setNetworkOperatorId($admin)
    {
        $this->sellerAddService->setNetworkOperatorId($this, $admin);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.seller.add-seller')
            ->extends('layouts.v1.app');
    }
}
