<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\PqrReplyService;
use App\Models\V1\Pqr;
use Livewire\Component;
use Livewire\WithFileUploads;
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
