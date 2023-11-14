<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientManualPaymentRegisterDetails;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Invoice;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ClientManualPaymentDetails extends Component
{
    use WithPagination;
    use WithFileUploads;
    use FilterTrait;

    public $model;
    public $invoice;
    public $evidence;
    public $client;
    public $register;

    private $clientManualPaymentRegisterDetails;

    public function __construct($id = null)
    {
        $this->clientManualPaymentRegisterDetails = ClientManualPaymentRegisterDetails::getInstance();
        parent::__construct($id);
    }

    public function mount(Invoice $invoice)
    {
        $this->clientManualPaymentRegisterDetails->mount($this, $invoice);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.client-manual-payment-details')
            ->extends('layouts.v1.app');
    }


}
