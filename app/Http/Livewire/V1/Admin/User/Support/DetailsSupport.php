<?php

namespace App\Http\Livewire\V1\Admin\User\Support;

use App\Http\Services\V1\Admin\User\Support\SupportDetailsService;
use App\Models\V1\Support;
use Livewire\Component;

class DetailsSupport extends Component
{
    public $model;
    private $detailsNetworkOperatorService;


    public function __construct($id = null)
    {
        $this->detailsNetworkOperatorService = SupportDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(Support $support)
    {
        $this->detailsNetworkOperatorService->mount($this, $support);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.support.detail-support')
            ->extends('layouts.v1.app');
    }
}
