<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientImportService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ClientImportComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    use FilterTrait;

    public $model;
    public $file;
    private $clientImportService;

    public function __construct($id = null)
    {
        $this->clientImportService = ClientImportService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        $this->clientImportService->mount($this, $client);
    }


    public function import()
    {
        $this->clientImportService->import($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.import-client')->extends('layouts.v1.app');
    }


}
