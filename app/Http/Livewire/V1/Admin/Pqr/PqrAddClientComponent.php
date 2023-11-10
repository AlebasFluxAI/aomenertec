<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\PqrAddClientService;
use App\Models\V1\Pqr;
use Livewire\Component;
use function view;

class PqrAddClientComponent extends Component
{
    public $model;
    public $client_id;
    public $clients = [];
    private $pqrAddClientService;

    public function __construct($id = null)
    {
        $this->pqrAddClientService = PqrAddClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Pqr $pqr)
    {
        $this->pqrAddClientService->mount($this, $pqr);
    }

    public function submitForm()
    {
        return $this->pqrAddClientService->submitForm($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.add-client-pqr')->extends('layouts.v1.app');
    }

}
