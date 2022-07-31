<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use App\Http\Services\V1\Admin\Pqr\PqrReplyService;
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

class PqrReplyComponent extends Component
{
    use WithFileUploads;
    public $description;
    public $model;
    public $messages;
    public $attach;
    protected $listeners =
        [
            "pqr_message_created" => 'refreshMessages',
        ];

    private $pqrReplyService;

    public function __construct($id = null)
    {
        $this->pqrReplyService = PqrReplyService::getInstance();
        parent::__construct($id);
    }

    public function refreshMessages()
    {
        $this->pqrReplyService->refreshMessages($this);
    }

    public function submitMessage()
    {
        $this->pqrReplyService->submitMessage($this);
    }

    public function mount(Pqr $pqr)
    {
        $this->pqrReplyService->mount($this, $pqr);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.reply-pqr'
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->pqrReplyService->getData($this);
    }
}
