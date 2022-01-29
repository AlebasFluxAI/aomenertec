<?php

namespace App\Http\Livewire\V1\Admin;

use Livewire\Component;
use function view;

class AddEquipment extends Component
{
    public function render()
    {
        return view('livewire.administrar.v1.add-equipment')
            ->extends('layouts.v1.app');
    }
}
