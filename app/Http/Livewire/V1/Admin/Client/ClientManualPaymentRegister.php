<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientManualPaymentRegisterService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Invoice;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ClientManualPaymentRegister extends Component
{
    use WithPagination;
    use WithFileUploads;
    use FilterTrait;

    public $model;
    public $invoice;
    public $evidence;
    public $client;
    public $payment_methods;
    public $payment_method;
    public $reference;
    public $other_payment_method;
    public $bank;
    private $clientManualPaymentRegisterService;

    public function __construct($id = null)
    {
        $this->clientManualPaymentRegisterService = ClientManualPaymentRegisterService::getInstance();
        parent::__construct($id);
    }

    public function mount(Invoice $invoice)
    {
        $this->clientManualPaymentRegisterService->mount($this, $invoice);
    }

    public function registerPayment()
    {
        $this->clientManualPaymentRegisterService->registerPayment($this);

    }

    public function render()
    {
        return view('livewire.v1.admin.client.client-manual-payment-register')
            ->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->clientManualPaymentRegisterService->getData($this);
    }
}
