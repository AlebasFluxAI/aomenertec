<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\DetailsPqrGuestClientService;
use App\Models\Traits\PassTrait;
use App\Models\V1\Pqr;
use Livewire\Component;
use Livewire\WithFileUploads;
use function view;

class DetailsPqrGuestClientComponent extends Component
{
    use PassTrait;
    use WithFileUploads;

    public $description;
    public $model;
    public $messages;
    public $attach;
    protected $listeners =
        [
            "pqr_message_created" => 'refreshMessages',
        ];

    private $adminPqrGuestClientService;

    public function __construct($id = null)
    {
        $this->adminPqrGuestClientService = DetailsPqrGuestClientService::getInstance();
        parent::__construct($id);
    }

    public function submitMessage()
    {
        $this->adminPqrGuestClientService->submitMessage($this);
    }

    public function refreshMessages()
    {
        $this->adminPqrGuestClientService->refreshMessages($this);
    }

    public function mount(Pqr $pqr)
    {
        $this->adminPqrGuestClientService->mount($this, $pqr);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.reply-pqr',
        )->extends('layouts.v1.app', ["without_header" => true]);
    }
}
