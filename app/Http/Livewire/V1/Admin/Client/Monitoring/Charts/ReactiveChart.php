<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Models\V1\Client;
use Livewire\Component;

class ReactiveChart extends Component
{
    protected $listeners = ['editAxisReactive', 'dateRangeReactive'];
    public $client;
    public $time_reactive_id;
    public $date_range_reactive;
    public $series_reactive;
    public $x_axis_reactive;
    public $reactive_variables;
    public $data_chart_reactive;
    public $start_reactive;
    public $end_reactive;

    public function mount(Client $client, $reactive_variables, $data_chart_reactive){
        $this->client = $client;
        $edit_index = [];
        $i=0;
        foreach ($reactive_variables as $data){
            $edit_index[$i] = $data;
            $i++;
        }
        $this->reactive_variables = $edit_index;
        $this->time_reactive_id = 2;
        $this->data_chart_reactive = $data_chart_reactive;
        $this->series_reactive = [];
        $this->x_axis_reactive = [];

    }

    public function editAxisReactive(){
        $this->series_reactive = [];
        $this->x_axis_reactive = [];
        if ($this->time_reactive_id == 1) {
            $data_chart = $this->client->hourlyMicrocontrollerData()->limit(60)->get();
        } elseif ($this->time_reactive_id == 2) {
            $data_chart = $this->client->dailyMicrocontrollerData()->limit(24)->get();
        } elseif ($this->time_reactive_id == 3) {
            $data_chart = $this->client->monthlyMicrocontrollerData()->limit(31)->get();
        } else {
            $data_chart = $this->client->annualMicrocontrollerData()->limit(12)->get();
        }
        $this->data_chart_reactive =$data_chart;
        $this->end_reactive = $this->data_chart_reactive->first()->microcontrollerData->source_timestamp;
        $this->start_reactive = $this->data_chart_reactive->last()->microcontrollerData->source_timestamp;
        $this->date_range_reactive = $this->start_reactive." - ".$this->end_reactive;
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

    public function updatedTimeReactiveId(){
        if ($this->time_reactive_id == 1) {
            $data_chart = $this->client->hourlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_reactive, $this->end_reactive])->get();
        } elseif ($this->time_reactive_id == 2) {
            $data_chart = $this->client->dailyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_reactive, $this->end_reactive])->get();
        } elseif ($this->time_reactive_id == 3) {
            $data_chart = $this->client->monthlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_reactive, $this->end_reactive])->get();
        } else {
            $data_chart = $this->client->annualMicrocontrollerData()
                ->whereBetween("created_at", [$this->start_reactive, $this->end_reactive])->get();
        }

        $array_aux = $data_chart->reverse();
        $this->series_reactive = [];
        $data_aux = [];
        $this->x_axis_reactive = [];
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
        $this->data_chart_reactive = $data_chart;
        $this->emit('changeAxisReactive', ['series_reactive' => $this->series_reactive,  'x_axis_reactive'=>$this->x_axis_reactive]);
    }

    public function dateRangeReactive($start, $end)
    {
        $this->date_range_reactive = $start." - ".$end;
        $this->start_reactive = $start;
        $this->end_reactive = $end;
        if ($this->time_reactive_id == 1) {
            $data_chart = $this->client->hourlyMicrocontrollerData()
                ->whereBetween("created_at", [$start, $end])->get();
        } elseif ($this->time_reactive_id == 2) {
            $data_chart = $this->client->dailyMicrocontrollerData()
                ->whereBetween("created_at", [$start, $end])->get();
        } elseif ($this->time_reactive_id == 3) {
            $data_chart = $this->client->monthlyMicrocontrollerData()
                ->whereBetween("created_at", [$start, $end])->get();
        } else {
            $data_chart = $this->client->annualMicrocontrollerData()
                ->whereBetween("created_at", [$start, $end])->get();
        }

        $array_aux = $data_chart->reverse();
        $this->series_reactive = [];
        $data_aux = [];
        $this->x_axis_reactive = [];
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
        $this->data_chart_reactive = $data_chart;
        $this->emit('changeAxisReactive', ['series_reactive' => $this->series_reactive,  'x_axis_reactive'=>$this->x_axis_reactive]);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.reactive-chart');
    }
}
