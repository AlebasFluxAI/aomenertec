<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientManualPaymentService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientManualPayment extends Component
{
    use WithPagination;
    use FilterTrait;

    public $model;
    private $clientManualPaymentService;

    public function __construct($id = null)
    {
        $this->clientManualPaymentService = ClientManualPaymentService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        $this->clientManualPaymentService->mount($this, $client);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.client-manual-payment', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->clientManualPaymentService->getData($this);
    }
}
