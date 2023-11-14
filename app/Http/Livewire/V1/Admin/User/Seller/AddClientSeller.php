<?php

namespace App\Http\Livewire\V1\Admin\User\Seller;

use App\Http\Services\V1\Admin\User\Seller\SellerAddClientService;
use App\Models\V1\Seller;
use Livewire\Component;

class AddClientSeller extends Component
{
    public $model;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $identification;
    public $clientsRelated;
    public $clients;
    public $client;
    public $client_picked;
    public $client_id;
    public $message_client;
    public $test;


    private $addClientSellerService;

    public function __construct($id = null)
    {
        $this->addClientSellerService = SellerAddClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Seller $seller)
    {
        $this->addClientSellerService->mount($this, $seller);
    }

    public function addClient()
    {
        $this->addClientSellerService->addClient($this);
    }

    public function updatedClient()
    {
        $this->addClientSellerService->updatedClient($this);
    }

    public function assignClient($client)
    {
        $this->addClientSellerService->assignClient($this, $client);
    }


    public function delete($client)
    {
        $this->addClientSellerService->delete($this, $client["id"]);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.seller.add-client-seller')
            ->extends('layouts.v1.app');
    }
}
