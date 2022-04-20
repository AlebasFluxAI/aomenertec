<?php

namespace App\Http\Livewire\V1\Admin\User\Supervisor;

use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorIndexService;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Supervisor;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class IndexSupervisor extends Component
{
    use WithPagination;
    use ValidateUserFormTrait;


    private $indexSupervisorService;

    public function __construct($id = null)
    {
        $this->indexSupervisorService = SupervisorIndexService::getInstance();
        parent::__construct($id);
    }

    public function details($id)
    {
        $this->indexSupervisorService->details($this, $id);
    }

    public function edit($id)
    {
        $this->indexSupervisorService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexSupervisorService->delete($this, $id);
    }

    public function addClients($id)
    {
        $this->indexSupervisorService->addClients($this, $id);
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
}
