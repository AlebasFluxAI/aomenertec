<?php

namespace App\Http\Livewire\V1\Admin\User\Seller;

use App\Http\Services\V1\Admin\User\Seller\SellerAddService;
use App\Models\Traits\AddUserFormTrait;
use App\Models\Traits\PassTrait;
use App\Models\Traits\ValidateUserFormTrait;
use Livewire\Component;

class AddSeller extends Component
{
    use ValidateUserFormTrait;
    use AddUserFormTrait;
    use PassTrait;

    public $message;
    public $picked;
    public $network_operators;
    public $network_operator;
    public $network_operator_id;


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

    public function updatedNetworkOperator()
    {
        $this->sellerAddService->updatedNetworkOperator($this);
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
