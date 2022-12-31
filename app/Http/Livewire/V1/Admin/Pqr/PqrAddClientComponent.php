<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\PqrAddClientService;
use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use App\Models\V1\Pqr;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;

class PqrAddClientComponent extends Component
{
    public $model;
    public $client_id;
    public $clients = [];
    private $pqrAddClientService;

    public function __construct($id = null)
    {
        $this->pqrAddClientService = PqrAddClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Pqr $pqr)
    {
        $this->pqrAddClientService->mount($this, $pqr);
    }

    public function submitForm()
    {
        return $this->pqrAddClientService->submitForm($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.add-client-pqr')->extends('layouts.v1.app');
    }

}
