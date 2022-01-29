<?php

namespace App\Http\Livewire;

use Livewire\Component;
use function view;

class Index extends Component
{
    public function render()
    {
        return view('livewire.administrar.v1.index')
            ->extends('layouts.v1.app');
        ;
    }
}
