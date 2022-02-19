<?php

namespace App\Http\Livewire\V1\Admin\Equipment;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use function view;

class AddEquipment extends Component
{
    public $name;
    public $serial;
    public $description;
    public $equipment_type_id;
    public $equipment_types;

    private $addEquipmentService;

    public function __construct($id = null)
    {
        $this->addEquipmentService = EquipmentAddService::getInstance();
        parent::__construct($id);

    }

    public function notifyNewOrder()
    {
        $name="hols";
    }

    public function mount()
    {
        $this->fill([
            'equipment_types' => [],
        ]);
    }

    public function loadEquipmentType()
    {
        event(new ChatEvent());
        $this->addEquipmentService->loadEquipmentType($this);

    }

    public function updatedSelectedState($state)
    {

        $this->addEquipmentService->updatedSelectedState($this, $state);
    }

    public function submitForm()
    {
        $this->addEquipmentService->submitForm($this);
    }
    public function render()
    {
        return view('livewire.administrar.v1.add-equipment')
            ->extends('layouts.v1.app');
    }
}
