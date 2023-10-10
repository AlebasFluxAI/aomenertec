<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientAlertIndexService;
use App\Http\Services\V1\Admin\Client\ClientImportIndexService;
use App\Http\Services\V1\Admin\Client\ClientImportService;
use App\Http\Services\V1\Admin\Client\IndexClientService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
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
