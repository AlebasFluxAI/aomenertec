<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\IndexClientService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use Livewire\Component;
use Livewire\WithPagination;

class IndexClient extends Component
{
    use WithPagination;


    private $indexClientService;

    public function __construct($id = null)
    {
        $this->indexClientService = IndexClientService::getInstance();
        parent::__construct($id);
    }

    public function getClient()
    {
        return $this->indexClientService->getClient();
    }

    public function details($id)
    {
        $this->indexClientService->details($this, $id);
    }

    public function edit($id)
    {
        $this->indexClientService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexClientService->delete($this, $id);
    }
    public function render()
    {
        return view('livewire.v1.admin.client.index-client', [
            "clients" => Client::paginate(15)
        ])->extends('layouts.v1.app');
    }
}
