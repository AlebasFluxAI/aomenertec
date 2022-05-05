<?php

namespace App\Http\Livewire\V1\Monitoring\Charts;

use App\Models\V1\Client;
use Livewire\Component;

class ReactiveChart extends Component
{
    protected $listeners = ['editAxisReactive'];
    public $client;
    public $time_reactive_id;
    public $date_range_reactive;
    public $series_reactive;
    public $x_axis_reactive;
    public $reactive_variables;
    public $data_chart_reactive;

    public function mount(Client $client, $reactive_variables, $data_chart_reactive){
        $this->client = $client;
        $edit_index = [];
        $i=0;
        foreach ($reactive_variables as $data){
            $edit_index[$i] = $data;
            $i++;
        }
        $this->reactive_variables = $edit_index;
        $this->time_reactive_id = 1;
        $this->data_chart_reactive = $data_chart_reactive;
        $this->series_reactive = [];
        $this->x_axis_reactive = [];

    }

    public function editAxisReactive(){
        $this->series_reactive = [];
        $this->x_axis_reactive = [];
        $array_aux = $this->data_chart_reactive->reverse();

        foreach ($this->reactive_variables as $index=>$data) {
            $data_aux[$index] = [];
            foreach ($array_aux as $item) {
                if ($this->time_reactive_id == 3 || $this->time_reactive_id == 4) {
                    $raw_json = json_decode($item->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                } else {
                    $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                }
                if ($index == 0){
                    array_push($this->x_axis_reactive, $item->microcontrollerData->source_timestamp);
                }
            }

            $this->series_reactive[$index] = ["name" => $data['variable_name'], "data"=> $data_aux[$index]];
        }

        $this->emit('changeAxisReactive', ['series_reactive' => $this->series_reactive,  'x_axis_reactive'=>$this->x_axis_reactive]);
    }
    public function render()
    {
        return view('livewire.v1.monitoring.charts.reactive-chart');
    }
}
