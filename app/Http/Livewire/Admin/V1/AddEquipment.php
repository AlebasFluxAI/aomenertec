<?php

namespace App\Http\Livewire\Admin\V1;

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
