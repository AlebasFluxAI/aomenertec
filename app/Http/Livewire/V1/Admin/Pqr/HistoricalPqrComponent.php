<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\HistoricalPqrService;
use App\Models\V1\Pqr;
use Livewire\Component;
use function view;

class HistoricalPqrComponent extends Component
{
    private $historicalPqrService;

    public function __construct($id = null)
    {
        $this->historicalPqrService = HistoricalPqrService::getInstance();
        parent::__construct($id);
    }

    public function mount(Pqr $pqr)
    {
        $this->historicalPqrService->mount($this, $pqr);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.historical-pqr',
        )->extends('layouts.v1.app');
    }
}
