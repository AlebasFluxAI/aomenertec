<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\AdminPqrGuestClientService;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;

class AdminPqrGuestClientComponent extends Component
{
    use PassTrait;
    use WithFileUploads;

    public $pqr_code;
    public $client_code;
    public $pqr;
    public $pqrs;
    public $subdomain;

    protected $rules = [
        'client_code' => 'required|exists:clients,code',
        'pqr_code' => 'required|exists:pqrs,code',
    ];
    private $adminPqrGuestClientService;

    public function __construct($id = null)
    {
        $this->adminPqrGuestClientService = AdminPqrGuestClientService::getInstance();
        parent::__construct($id);
    }

    public function closePqr($id)
    {
        return $this->pqrIndexService->closePqr($this, $id);
    }

    public function openTicked($id)
    {
        return $this->adminPqrGuestClientService->openTicked($this, $id);
    }

    public function resolvedTicked($id)
    {
        return $this->adminPqrGuestClientService->resolvedTicked($this, $id);
    }

    public function closedTicked($id)
    {
        return $this->adminPqrGuestClientService->closedTicked($this, $id);
    }

    public function submitForm()
    {
        $this->adminPqrGuestClientService->submitForm($this);
    }

    public function mount()
    {
        $this->adminPqrGuestClientService->mount($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.admin-pqr-guest-client',
        )->extends('layouts.v1.app');
    }
}
