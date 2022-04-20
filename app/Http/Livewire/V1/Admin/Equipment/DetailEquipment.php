<?php

namespace App\Http\Livewire\V1\Admin\Equipment;

use App\Http\Services\V1\Admin\Equipment\EquipmentDetailService;
use App\Models\V1\Equipment;
use Livewire\Component;
use function view;

class DetailEquipment extends Component
{
    public $equipment;
    private $detailEquipmentService;


    public function __construct($id = null)
    {
        $this->detailEquipmentService = EquipmentDetailService::getInstance();
        parent::__construct($id);
    }

    public function mount(Equipment $equipment)
    {
        $this->detailEquipmentService->mount($this, $equipment);
    }


    public function render()
    {
        return view('livewire.v1.admin.equipment.detail-equipment')
            ->extends('layouts.v1.app');
    }
}
