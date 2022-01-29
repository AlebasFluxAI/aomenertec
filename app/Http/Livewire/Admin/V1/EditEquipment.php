<?php

namespace App\Http\Livewire\Admin\V1;

use Livewire\Component;
use function view;

class EditEquipment extends Component
{
    public function render()
    {
        return view('livewire.administrar.v1.edit-equipment')
            ->extends('layouts.v1.app');
    }
}
