<?php

namespace App\Http\Livewire\Administrar\v1;

use Livewire\Component;

class AddEquipment extends Component
{
    public function render()
    {
        return view('livewire.administrar.v1.add-equipment')
            ->extends('layouts.v1.app');
    }
}
