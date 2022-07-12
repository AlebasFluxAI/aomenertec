<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\AddPqrSupervisorService;
use App\Http\Services\V1\Admin\Pqr\AddPqrSupportService;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;

class AddPqrSupervisorComponent extends Component
{
    use PassTrait;
    use WithFileUploads;


    public $pqr_type;
    public $pqr_types;
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
    public $model;
    public $client_code;
    public $has_client_code;

    private $addPqrSupportService;

    public function __construct($id = null)
    {
        $this->addPqrSupportService = AddPqrSupervisorService::getInstance();
        parent::__construct($id);
    }

    public function closePqr($pqr)
    {
        $this->addPqrSupportService->closePqr($this, $pqr);
    }

    public function updatedPqrType()
    {
        $this->addPqrSupportService->updateType($this);
    }

    public function submitForm()
    {
        $this->addPqrSupportService->submitForm($this);
    }

    public function mount()
    {
        $this->addPqrSupportService->mount($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.add-pqr-supervisor',
        )->extends('layouts.v1.app');
    }
}
