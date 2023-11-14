<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Invoice;


use App\Http\Services\V1\Admin\Invoicing\Invoice\InvoiceGuestClientService;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use function view;

class InvoiceGuestClientComponent extends Component
{
    public $has_client_code;
    public $client_code;
    public $contact_identification;
    public $model;
    public $subdomain;
    private $invoiceGuestClientService;

    public function __construct($id = null)
    {
        $this->invoiceGuestClientService = InvoiceGuestClientService::getInstance();
        parent::__construct($id);
    }

    public function mount()
    {
        $this->subdomain = Route::input("subdomain");
    }

    public function submitForm()
    {
        $this->invoiceGuestClientService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.invoicing.invoice.invoice-guest-client')->extends('layouts.v1.app');
    }

}
