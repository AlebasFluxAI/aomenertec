<?php

namespace App\Http\Livewire\V1\Admin\WorkOrder;

use App\Http\Services\V1\Admin\WorkOrder\WorkOrderSolverService;
use App\Models\Traits\ClientFormTrait;
use App\Models\V1\WorkOrder;
use Livewire\Component;
use Livewire\WithFileUploads;

class WorkOrderSolver extends Component
{
    use WithFileUploads;
    use ClientFormTrait;

    public $model;
    public $solution_description;
    public $set_execution_time;
    public $execution_time_hours;
    public $execution_time_minutes;
    public $equipment_type;
    public $equipment_picked;
    public $bachelors_equipments = [];
    public $equipment_serial;
    public $equipment_types;
    public $equipment;
    public $equipment_type_id;
    public $evidences = [];
    public $final_recommendations;


    private $workOrderSolverService;

    public function __construct()
    {
        parent::__construct();
        $this->workOrderSolverService = WorkOrderSolverService::getInstance();
    }

    public function mount(WorkOrder $workOrder)
    {
        $this->workOrderSolverService->mount($this, $workOrder);
    }

    public function submitForm()
    {
        $this->workOrderSolverService->submitForm($this);
    }

    public function updated($property_name, $value)
    {
        $this->workOrderSolverService->updated($this, $property_name, $value);
    }


    public function assignEquipment($equipment)
    {
        $this->workOrderSolverService->assignEquipment($this, $equipment);
    }


    public function render()
    {
        return view('livewire.v1.admin.workOrder.solver-work-order')
            ->extends('layouts.v1.app');
    }
}
