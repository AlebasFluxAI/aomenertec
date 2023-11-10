<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\PqrCloseService;
use App\Models\V1\Pqr;
use Livewire\Component;
use Livewire\WithFileUploads;
use function view;

class PqrCloseComponent extends Component
{
    use WithFileUploads;

    public $description;
    public $model;
    public $messages;
    public $attach;

    private $pqrCloseService;

    public function __construct($id = null)
    {
        $this->pqrCloseService = PqrCloseService::getInstance();
        parent::__construct($id);
    }

    public function submitCloserMessage()
    {
        $this->pqrCloseService->submitCloserMessage($this);
    }

    public function mount(Pqr $pqr)
    {
        $this->pqrCloseService->mount($this, $pqr);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.close-pqr'
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->pqrCloseService->getData($this);
    }
}
