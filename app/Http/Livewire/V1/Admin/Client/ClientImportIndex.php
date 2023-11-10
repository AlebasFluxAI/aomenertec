<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientImportIndexService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ClientImportIndex extends Component
{
    use WithPagination;
    use WithFileUploads;
    use FilterTrait;

    public $model;
    public $file;
    private $clientImportIndexService;

    public function __construct($id = null)
    {
        $this->clientImportIndexService = ClientImportIndexService::getInstance();
        parent::__construct($id);
    }

    public function import()
    {
        $this->clientImportIndexService->import($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.import-client-index', [
            "data" => $this->getData()
        ])
            ->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->clientImportIndexService->getData();
    }


}
