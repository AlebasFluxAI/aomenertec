<?php

namespace App\Http\Livewire\V1\Monitoring;

use Livewire\Component;

class Monitoring extends Component
{
    protected $listeners = ['echo:real-time-monitoring,.dataEvent' => 'newData'];


    public $data;
    public $test;

    public function mount()
    {
        $this->data = [];
        $this->test = [];
    }

   /* public function getListeners()
    {
        return [
            "echo-private:real-time-monitoring.{$this->raw_json['client_id']},RealTimeMonitoringEvent" => 'newData',
        ];
    }*/
    public function newData($data){
        $this->test = $data['data'];
    }
    public function render()
    {
        return view('livewire.v1.monitoring.monitoring')
            ->extends('layouts.v1.app');
    }
}
