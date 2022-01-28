<?php

namespace App\Http\Livewire\Administrar\v1;

use Livewire\Component;

class EditEquipment extends Component
{
    public function render()
    {
        return view('livewire.administrar.v1.edit-equipment')
            ->extends('layouts.v1.app');
    }
}
