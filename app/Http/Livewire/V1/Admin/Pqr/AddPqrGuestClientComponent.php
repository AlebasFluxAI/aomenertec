<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;

class AddPqrGuestClientComponent extends Component
{
    use PassTrait;
    use WithFileUploads;

    public $invoice_radio_button;
    public $platform_radio_button;
    public $tech_radio_button;
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
        )->extends('layouts.v1.app');
    }
}
