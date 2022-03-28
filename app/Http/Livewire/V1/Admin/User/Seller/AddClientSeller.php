<?php

namespace App\Http\Livewire\V1\Admin\User\Seller;

use App\Http\Services\V1\Admin\User\Seller\SellerAddClientService;
use App\Http\Services\V1\Admin\User\Seller\SellerAddService;
use App\Http\Services\V1\Admin\User\Seller\SellerEditService;
use App\Models\V1\Seller;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddClientSeller extends Component
{
    public $model;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $identification;
    public $clients;
    public $client;
    public $client_picked;
    public $client_id;
    public $message_client;
    private $editSellerService;

    public function __construct($id = null)
    {
        $this->editSellerService = SellerAddClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Seller $seller)
    {
        $this->editSellerService->mount($this, $seller);
    }

    public function addClient()
    {
        $this->editSellerService->addClient($this);
    }

    public function updatedClient()
    {
        $this->editSellerService->updatedClient($this);
    }

    public function assignClient($client)
    {
        $this->editSellerService->assignClient($this, $client);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.seller.add-client-seller')
            ->extends('layouts.v1.app');
    }
}
