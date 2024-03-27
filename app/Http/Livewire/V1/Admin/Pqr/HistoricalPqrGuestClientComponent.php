<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\HistoricalPqrGuestClientService;
use App\Models\Traits\PassTrait;
use App\Models\V1\Pqr;
use Livewire\Component;
use Livewire\WithFileUploads;
use function view;

class HistoricalPqrGuestClientComponent extends Component
{
    use PassTrait;
    use WithFileUploads;

    public $model;
    public $client_code;

    protected $rules = [
        'client_code' => 'required|exists:clients,code',
        'pqr_code' => 'required|exists:pqrs,code',
    ];
    private $historicalPqrGuestClientService;

    public function __construct($id = null)
    {
        $this->historicalPqrGuestClientService = HistoricalPqrGuestClientService::getInstance();
        parent::__construct($id);
    }

    public function closePqr($pqr)
    {
        $this->historicalPqrGuestClientService->closePqr($this, $pqr);
    }

    public function rejectPqrForm()
    {
        $this->historicalPqrGuestClientService->processingPqr($this, $this->model->id);
    }

    public function updatedPqrType()
    {
        $this->historicalPqrGuestClientService->updateType($this);
    }

    public function submitForm()
    {
        $this->historicalPqrGuestClientService->submitForm($this);
    }

    public function closePqrForm()
    {
        $this->historicalPqrGuestClientService->closePqrForm($this);
    }


    public function mount(Pqr $pqr)
    {
        $this->historicalPqrGuestClientService->mount($this, $pqr);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.historical-pqr-guest-client',
        )->extends('layouts.v1.app', ["without_header" => true]);
    }
}
