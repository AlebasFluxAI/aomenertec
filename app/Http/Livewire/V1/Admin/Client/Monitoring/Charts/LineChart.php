<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Models\V1\Client;
use Livewire\Component;

class LineChart extends Component
{
    protected $listeners = ['changeVariable', 'changeTime', 'changeDateRange', 'startDateRange'];

    public $x_axis;
    public $series;
    public $data_chart;
    public $variables_selected;
    public $time;
    public $client;
    public $start;
    public $end;
    public $chart_type;
    public function mount(Client $client, $variables_selected, $time, $chart_type, $data_chart)
    {

        $this->time = $time;
        $this->chart_type = $chart_type;
        $this->client =  $client;
        $this->data_chart = $data_chart;
        $this->end = $data_chart->last()->microcontrollerData->source_timestamp;
        $this->start = $data_chart->first()->microcontrollerData->source_timestamp;
        $this->variables_selected =$variables_selected;
        $array_aux = $data_chart->reverse();
        $this->series = [];
        $data_aux = [];
        $this->x_axis = [];
        foreach ($this->variables_selected as $index=>$data) {
            $data_aux[$index] = [];
            foreach ($array_aux as $item) {
                if ($this->time == 3 || $this->time == 4){
                    $raw_json = json_decode($item->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                } else{
                    $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                }
                if ($index == 0) {
                    array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
                }
            }


            $this->series[$index] = ["name" => $data['variable_name'], "type"=>$this->chart_type, "data"=> $data_aux[$index]];
        }


    }

    public function startDateRange()
    {

        if ($this->time == 1) {
            $data_chart = $this->client->hourlyMicrocontrollerData()->limit(60)->get();
        } elseif ($this->time == 2) {
            $data_chart = $this->client->dailyMicrocontrollerData()->limit(24)->get();
        } elseif ($this->time == 3) {
            $data_chart = $this->client->monthlyMicrocontrollerData()->limit(31)->get();
        } else {
            $data_chart = $this->client->annualMicrocontrollerData()->limit(12)->get();
        }

        $array_aux = $data_chart->reverse();
        $this->series = [];
        $data_aux = [];
        $this->x_axis = [];
        foreach ($this->variables_selected as $index=>$data) {
            $data_aux[$index] = [];
            foreach ($array_aux as $item) {
                if ($this->time == 3 || $this->time == 4){
                    $raw_json = json_decode($item->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                } else{
                    $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                }
                if ($index == 0) {
                    array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
                }
            }
            $this->series[$index] = ["name" => $data['variable_name'], "type"=>$this->chart_type, "data"=> $data_aux[$index]];
        }

        $this->data_chart = $data_chart;
        $this->emit('changeAxis', ['series' => $this->series,  'x_axis'=>$this->x_axis]);
    }
    public function changeDateRange($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
        if ($this->time == 1) {
            $data_chart = $this->client->hourlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start, $this->end])->get();
        } elseif ($this->time == 2) {
            $data_chart = $this->client->dailyMicrocontrollerData()
            ->whereBetween("created_at", [$this->start, $this->end])->get();
        } elseif ($this->time == 3) {
            $data_chart = $this->client->monthlyMicrocontrollerData()
            ->whereBetween("created_at", [$this->start, $this->end])->get();
        } else {
            $data_chart = $this->client->annualMicrocontrollerData()
                ->whereBetween("created_at", [$this->start, $this->end])->get();
        }

        $array_aux = $data_chart->reverse();
        $this->series = [];
        $data_aux = [];
        $this->x_axis = [];
        foreach ($this->variables_selected as $index=>$data) {
            $data_aux[$index] = [];
            foreach ($array_aux as $item) {
                if ($this->time == 3 || $this->time == 4){
                    $raw_json = json_decode($item->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                } else{
                    $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                }
                if ($index == 0) {
                    array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
                }
            }
            $this->series[$index] = ["name" => $data['variable_name'], "type"=>$this->chart_type, "data"=> $data_aux[$index]];
        }
        $this->data_chart = $data_chart;
        $this->emit('changeAxis', ['series' => $this->series,  'x_axis'=>$this->x_axis]);
    }
    public function changeTime($time)
    {
        if ($time == 1) {
            $data_chart = $this->client->hourlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start, $this->end])->get();
        } elseif ($time == 2) {
            $data_chart = $this->client->dailyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start, $this->end])->get();
        } elseif ($time == 3) {
            $data_chart = $this->client->monthlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start, $this->end])->get();
        } else {
            $data_chart = $this->client->annualMicrocontrollerData()
                            ->whereBetween("created_at", [$this->start, $this->end])->get();
        }
        $array_aux = $data_chart->reverse();
        $this->series = [];
        $data_aux = [];
        $this->x_axis = [];
        foreach ($this->variables_selected as $index=>$data) {
            $data_aux[$index] = [];
            foreach ($array_aux as $item) {
                if ($time == 3 || $time == 4){
                    $raw_json = json_decode($item->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                } else{
                    $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                }
                if ($index == 0) {
                    array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
                }
            }
            $this->series[$index] = ["name" => $data['variable_name'], "type"=>$this->chart_type, "data"=> $data_aux[$index]];
        }
        $this->data_chart = $data_chart;
        $this->time = $time;
        $this->emit('changeAxis', ['series' => $this->series,  'x_axis'=>$this->x_axis]);
    }

    public function changeVariable($variables, $chart_type)
    {
        $this->chart_type = $chart_type;
        $this->variables_selected = $variables;
        $array_aux = $this->data_chart->reverse();
        $this->series = [];
        $data_aux = [];
        foreach ($this->variables_selected as $index=>$data) {
            $data_aux[$index] = [];
            foreach ($array_aux as $item) {
                if ($this->time == 3 || $this->time == 4){
                    $raw_json = json_decode($item->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                } else{
                    $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                    array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                }
            }
            $this->series[$index] = ["name" => $data['variable_name'], "type"=>$this->chart_type, "data"=> $data_aux[$index]];
        }
        $this->emit('changeAxis', ['series' => $this->series,  'x_axis'=>$this->x_axis]);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.line-chart');
    }
}
