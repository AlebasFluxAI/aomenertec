<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use App\Models\V1\Pqr;
use Livewire\Component;
use function view;

class PqrDetailsComponent extends Component
{
    public $model;
    private $pqrIndexService;

    public function __construct($id = null)
    {
        $this->pqrIndexService = PqrIndexService::getInstance();
        parent::__construct($id);
    }

    public function mount(Pqr $pqr)
    {
        $this->pqrIndexService->mount($this, $pqr);
    }

    public function changeLevel($id)
    {
        return $this->pqrIndexService->changeLevel($this, $id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.details-pqr',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->pqrIndexService->getData($this);
    }
}
