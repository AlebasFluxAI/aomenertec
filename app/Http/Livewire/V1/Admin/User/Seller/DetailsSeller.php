<?php

namespace App\Http\Livewire\V1\Admin\User\Seller;

use App\Http\Services\V1\Admin\User\Seller\SellerDetailsService;
use App\Models\V1\Seller;
use Livewire\Component;

class DetailsSeller extends Component
{
    public $model;
    private $detailsSellerService;


    public function __construct($id = null)
    {
        $this->detailsSellerService = SellerDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(Seller $seller)
    {
        $this->detailsSellerService->mount($this, $seller);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.seller.detail-seller')
            ->extends('layouts.v1.app');
    }
}
