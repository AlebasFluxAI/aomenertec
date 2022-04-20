<?php

namespace App\Http\Livewire\V1\Monitoring;

use App\Models\V1\Client;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Livewire\Component;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class Monitoring extends Component
{
    protected $listeners = ['echo:data-monitoring,.dataEventAdd' => 'addData'];


    public $data;
    public $test;

    public function mount()
    {
        $unixTime = time();//delete
        $current_time = new \DateTime();
        $current_time->setTimestamp($unixTime);
        $current_hour = new \DateTime();
        $aux = $unixTime - ($unixTime%3600);//delete
        $current_hour->setTimestamp($aux);
        $this->data = Client::find(2)->microcontrollerData->whereBetween("source_timestamp", [$current_hour->format('Y-m-d H:i:s'), $current_time->format('Y-m-d H:i:s')]);
        $this->test = [];


    }

    /* public function getListeners()
     {
         return [
             "echo-private:real-time-monitoring.{$this->raw_json['client_id']},RealTimeMonitoringEvent" => 'newData',
         ];
     }*/
    public function newData($data)
    {
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> develop_v2
        $this->test = $data['data'];
    }
    public function addData(){
        $unixTime = time();//delete
        $current_time = new \DateTime();
        $current_time->setTimestamp($unixTime);
        $current_hour = new \DateTime();
        $aux = $unixTime - ($unixTime%3600);//delete
        $current_hour->setTimestamp($aux);
        $this->data = Client::find(2)->microcontrollerData->whereBetween("source_timestamp", [$current_hour->format('Y-m-d H:i:s'), $current_time->format('Y-m-d H:i:s')]);
<<<<<<< HEAD
=======
=======
        return [
            "echo-private:real-time-monitoring.{$this->raw_json['client_id']},RealTimeMonitoringEvent" => 'newData',
        ];
    }*/
    /*public function newData($data){
>>>>>>> 841826f7ca9fd2b0b887509f916d2701174f94cd
>>>>>>> develop_v2

    }
    public function render()
    {
        $lineChartModel = (new LineChartModel())
            ->setTitle('Voltaje')
            ->setAnimated(true)
            ->setDataLabelsEnabled(false)
            ->withLegend()
        ;
        foreach ($this->data as $item){
            $data=json_decode($item->raw_json);
            $explode_time = explode(" ", $item->source_timestamp);
            $lineChartModel->addPoint($explode_time[1],round($data->ph1_volt, 2));
        }
        return view('livewire.v1.monitoring.monitoring')
            ->with(["lineChartModel" => $lineChartModel])
            ->extends('layouts.v1.app');
    }
}
