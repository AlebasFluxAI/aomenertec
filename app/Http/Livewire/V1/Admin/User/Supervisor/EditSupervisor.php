<?php

namespace App\Http\Livewire\V1\Admin\User\Supervisor;


use App\Http\Services\V1\Admin\User\SuperAdmin\NetworkOperatorEditService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorEditService;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class EditSupervisor extends Component
{
    public $model;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $identification;
    private $supervisorEditService;

    public function __construct($id = null)
    {
        $this->supervisorEditService = SupervisorEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(SuperAdmin $superAdmin)
    {
        $this->supervisorEditService->mount($this, $superAdmin);
    }


    public function submitForm()
    {
        $this->supervisorEditService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.supervisor.edit-supervisor')
            ->extends('layouts.v1.app');
    }
}
