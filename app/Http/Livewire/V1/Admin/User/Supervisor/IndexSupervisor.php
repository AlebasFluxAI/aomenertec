<?php

namespace App\Http\Livewire\V1\Admin\User\Supervisor;

use App\Http\Services\V1\Admin\User\Supervisor\SupervisorIndexService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class IndexSupervisor extends Component
{
    use WithPagination;
    use FilterTrait;


    private $indexSupervisorService;

    public function __construct($id = null)
    {
        $this->indexSupervisorService = SupervisorIndexService::getInstance();
        parent::__construct($id);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.supervisor.index-supervisor', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexSupervisorService->getData($this);
    }

    public function deleteSupervisor($id)
    {
        $this->indexSupervisorService->deleteSupervisor($this, $id);
    }

    public function disableSupervisor($id)
    {
        $this->indexSupervisorService->disableSupervisor($this, $id);
    }

    public function getEnabledSupervisor($id)
    {
        return $this->indexSupervisorService->getEnabledSupervisor($this, $id);
    }

    public function getEnabledAuxSupervisor($id)
    {
        return $this->indexSupervisorService->getEnabledAuxSupervisor($this, $id);
    }

    public function conditionalDeleteSupervisor($id)
    {
        return $this->indexSupervisorService->conditionalDeleteSupervisor($this, $id);
    }

    public function conditionalLinkClientsSupervisor($id)
    {
        return $this->indexSupervisorService->conditionalLinkClientsSupervisor($this, $id);
    }
}
