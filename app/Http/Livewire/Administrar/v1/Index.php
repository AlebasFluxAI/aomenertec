<?php

namespace App\Http\Livewire\Administrar\v1;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.administrar.v1.index')
            ->extends('layouts.v1.app');
        ;
    }
}
