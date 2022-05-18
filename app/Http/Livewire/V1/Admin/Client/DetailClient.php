<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\DetailClientService;
use App\Models\V1\Client;
use Livewire\Component;

class DetailClient extends Component
{
    public $client;
    public $equipments;
    private $detailClientService;


    public function __construct()
    {
        parent::__construct();
        $this->detailClientService = DetailClientService::getInstance();
    }

    public function mount(Client $client)
    {
        $this->detailClientService->mount($this, $client);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.detail-client')
            ->extends('layouts.v1.app');
    }
}
