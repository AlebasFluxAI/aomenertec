<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientImportDetailsService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Import;
use Livewire\Component;
use Livewire\WithPagination;

class ClientImportDetails extends Component
{
    use WithPagination;
    use FilterTrait;

    public $model;
    private $clientImportDetailsService;

    public function __construct($id = null)
    {
        $this->clientImportDetailsService = ClientImportDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(Import $import)
    {
        $this->clientImportDetailsService->mount($this, $import);
    }

    public function completedStatus($importItemId)
    {
        return $this->clientImportDetailsService->completedStatus($importItemId);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.import-client-details', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->clientImportDetailsService->getData($this);

    }

}
