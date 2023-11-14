<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientHandReadingIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientHandReadingIndex extends Component
{
    use WithPagination;
    use FilterTrait;

    private $clientHandReadingIndexService;

    public function __construct($id = null)
    {
        $this->clientHandReadingIndexService = ClientHandReadingIndexService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        $this->clientHandReadingIndexService->mount($this, $client);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.client-hand-reading', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->clientHandReadingIndexService->getData($this);
    }
}
