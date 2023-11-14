<?php

namespace App\Http\Livewire;

use Livewire\Component;
use function view;

class Index extends Component
{
    public function render()
    {
        return view('livewire.v1.admin.index')
            ->extends('layouts.v1.app');;
    }
}
