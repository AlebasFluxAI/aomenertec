<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\CreatedPqrGuestClientService;
use App\Models\Traits\PassTrait;
use App\Models\V1\Pqr;
use Livewire\Component;
use Livewire\WithFileUploads;
use function view;

class CreatedPqrGuestClientComponent extends Component
{
    use PassTrait;
    use WithFileUploads;

    public $model;

    private $createdPqrGuestClientService;

    public function __construct($id = null)
    {
        $this->createdPqrGuestClientService = CreatedPqrGuestClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Pqr $pqr)
    {
        $this->createdPqrGuestClientService->mount($this, $pqr);
    }


    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.created-pqr-guest-client',
        )->extends('layouts.v1.app', ["without_header" => true]);
    }
}
