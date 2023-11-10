<?php

namespace App\Http\Livewire\V1\Admin\User\Seller;

use App\Http\Services\V1\Admin\User\Seller\SellerEditService;
use App\Models\V1\Seller;
use Livewire\Component;

class EditSeller extends Component
{
    public $model;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $identification;
    public $indicatives;
    public $indicative;
    private $editSellerService;

    public function __construct($id = null)
    {
        $this->editSellerService = SellerEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(Seller $seller)
    {
        $this->editSellerService->mount($this, $seller);
    }

    public function submitForm()
    {
        $this->editSellerService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.seller.edit-seller')
            ->extends('layouts.v1.app');
    }
}
