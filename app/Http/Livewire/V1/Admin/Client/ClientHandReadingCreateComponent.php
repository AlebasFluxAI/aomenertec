<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientHandReadingCreateService;
use App\Models\V1\WorkOrder;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClientHandReadingCreateComponent extends Component
{
    use WithFileUploads;

    public $model;
    public $evidences = [];
    private $clientHandReadingCreateService;

    public function __construct()
    {
        parent::__construct();
        $this->clientHandReadingCreateService = ClientHandReadingCreateService::getInstance();
    }

    public function submitForm()
    {
        $this->clientHandReadingCreateService->submitForm($this);
    }

    public function mount(WorkOrder $work_order = null)
    {
        $this->clientHandReadingCreateService->mount($this, $work_order);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.client-hand-reading-create-component')->extends('layouts.v1.app');
    }

    protected function rules()
    {
        return $this->clientHandReadingCreateService->rules($this);
    }
}
