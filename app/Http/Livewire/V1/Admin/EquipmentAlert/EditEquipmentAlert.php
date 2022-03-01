<?php

namespace App\Http\Livewire\V1\Admin\EquipmentAlert;

use App\Models\V1\Equipment;
use Livewire\Component;
use function view;

class EditEquipmentAlert extends Component
{
    public function mount(Equipment $equipment)
    {
        dd($equipment);
    }

    public function render()
    {
        return view('livewire.administrar.v1.equipmentAlert.edit-equipment-alert')
            ->extends('layouts.v1.app');
    }
}
