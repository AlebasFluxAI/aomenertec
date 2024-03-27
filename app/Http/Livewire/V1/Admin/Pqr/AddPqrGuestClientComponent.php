<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Models\Traits\PassTrait;
use Livewire\Component;
use Livewire\WithFileUploads;
use function view;

class AddPqrGuestClientComponent extends Component
{
    use PassTrait;
    use WithFileUploads;

    public $invoice_radio_button;
    public $platform_radio_button;
    public $subdomain;
    public $pqr_types;
    public $pqr_type;
    public $pqr_category;
    public $subject;
    public $attach;
    public $description;
    public $pqr_categories;
    public $severities;
    public $identification;
    public $severity;
    public $contact_name;
    public $contact_phone;
    public $details;
    public $contact_identification;
    public $contact_email;
    public $has_client_code;
    public $client_code;
    public $request_equipment;

    protected $rules = [
        'contact_identification' => 'required',
        'contact_phone' => 'required|min:10|max:10|exists:clients,phone|exists:clients,phone',
        'contact_email' => 'required|exists:clients,email|exists:clients,email',
    ];
    private $addPqrGuestClientService;

    public function __construct($id = null)
    {
        $this->addPqrGuestClientService = AddPqrGuestClientService::getInstance();
        parent::__construct($id);
    }

    public function closePqr($pqr)
    {
        $this->addPqrGuestClientService->closePqr($this, $pqr);
    }


    public function solvePqr($pqr)
    {
        $this->addPqrGuestClientService->solvePqr($this, $pqr);
    }

    public function updatedPqrType()
    {
        $this->addPqrGuestClientService->updateType($this);
    }

    public function submitForm()
    {
        $this->addPqrGuestClientService->submitForm($this);
    }

    public function mount()
    {
        $this->addPqrGuestClientService->mount($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.add-pqr-guest-client',
        )->extends('layouts.v1.app', ["without_header" => true]);
    }
}
