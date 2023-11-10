<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientHandReadingDetailService;
use App\Models\V1\MicrocontrollerData;
use Livewire\Component;

class ClientHandReadingDetailComponent extends Component
{
    public $model;
    public $client;
    private $clientHandReadingDetailService;

    public function __construct()
    {
        parent::__construct();
        $this->clientHandReadingDetailService = ClientHandReadingDetailService::getInstance();
    }

    public function mount(MicrocontrollerData $microcontroller_data)
    {

        $this->clientHandReadingDetailService->mount($this, $microcontroller_data);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.client-hand-reading-detail-component')->extends('layouts.v1.app');
    }
}
