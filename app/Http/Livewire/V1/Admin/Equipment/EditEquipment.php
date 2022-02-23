<?php

namespace App\Http\Livewire\V1\Admin\Equipment;

use App\Models\V1\Equipment;
use Livewire\Component;
use function view;

class EditEquipment extends Component
{
    public function mount(Equipment $equipment)
    {
        dd($equipment);
    }
    public function render()
    {
        return view('livewire.administrar.v1.equipment.edit-equipment')
            ->extends('layouts.v1.app');
    }
}
