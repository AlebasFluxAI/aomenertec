<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;

use Livewire\Component;

class Control extends Component
{
    public $test;

    public function mount(){
        $this->test = false;
    }

    public function test(){
        $this->test = !$this->test;
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.control');
    }
}
