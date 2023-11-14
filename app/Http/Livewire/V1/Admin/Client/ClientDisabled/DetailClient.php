<?php

namespace App\Http\Livewire\V1\Admin\Client\ClientDisabled;

use App\Http\Services\V1\Admin\ClientDisabled\DetailsClientDisabledService;
use Livewire\Component;

class DetailClient extends Component
{
    public $client;
    public $equipment;
    private $detailClientService;


    public function __construct()
    {
        parent::__construct();
        $this->detailClientService = DetailsClientDisabledService::getInstance();
    }

    public function mount($client)
    {
        $this->detailClientService->mount($this, $client);
    }


    public function render()
    {
        return view('livewire.v1.admin.client.clientDisabled.detail-client-disabled')
            ->extends('layouts.v1.app');
    }
}
