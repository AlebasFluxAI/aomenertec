<?php

namespace App\Http\Livewire\V1\Monitoring\Charts;

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
        $this->emit('loading');
        $this->time = $time;
        $this->chart_type = $chart_type;
        $this->client =  $client;
        $this->data_chart = $data_chart;
        $this->end = $data_chart->last()->microcontrollerData->source_timestamp;
        $this->start = $data_chart->first()->microcontrollerData->source_timestamp;
        $this->variables_selected =$variables_selected;
        $array_aux = $data_chart->reverse();
        $this->series = [];
        $data = [];
        $this->x_axis = [];
        foreach ($this->variables_selected as $index=>$data) {
            $data[$index] = [];
            foreach ($array_aux as $item) {
                $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                array_push($data[$index], round($raw_json[$data['variable_name']], 2));
                array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
            }
                $this->series[$index] = ["name" => $data['variable_name'], "type"=>$this->chart_type, "data"=> $data[$index]];
        }
    }

    public function startDateRange(){
        $this->emit('loading');

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
        $data = [];
        $this->x_axis = [];
        foreach ($this->variables_selected as $index=>$data) {
            $data[$index] = [];
            foreach ($array_aux as $item) {
                $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                array_push($data[$index], round($raw_json[$data['variable_name']], 2));
                array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
            }
            $this->series[$index] = ["name" => $data['variable_name'], "type"=>$this->chart_type, "data"=> $data[$index]];
        }
        $this->data_chart = $data_chart;
        $this->emit('changeAxis', ['series' => $this->series,  'x_axis'=>$this->x_axis]);

    }
    public function changeDateRange($start, $end){
        $this->emit('loading');
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
        $data = [];
        $this->x_axis = [];
        foreach ($this->variables_selected as $index=>$data) {
            $data[$index] = [];
            foreach ($array_aux as $item) {
                $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                array_push($data[$index], round($raw_json[$data['variable_name']], 2));
                array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
            }
            $this->series[$index] = ["name" => $data['variable_name'], "type"=>$this->chart_type, "data"=> $data[$index]];
        }
        $this->data_chart = $data_chart;
        $this->emit('changeAxis', ['series' => $this->series,  'x_axis'=>$this->x_axis]);
    }
    public function changeTime($time)
    {$this->emit('loading');

        $this->time = $time;
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
        $data = [];
        $this->x_axis = [];
        foreach ($this->variables_selected as $index=>$data) {
            $data[$index] = [];
            foreach ($array_aux as $item) {
                $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                array_push($data[$index], round($raw_json[$data['variable_name']], 2));
                array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
            }
            $this->series[$index] = ["name" => $data['variable_name'], "type"=>$this->chart_type, "data"=> $data[$index]];
        }
        $this->data_chart = $data_chart;
        $this->emit('changeAxis', ['series' => $this->series,  'x_axis'=>$this->x_axis]);
    }

    public function changeVariable($variables, $chart_type)
    {
        $this->emit('loading');
        $this->chart_type = $chart_type;
        $this->variables_selected = $variables;
        $array_aux = $this->data_chart->reverse();
        $this->series = [];
        $data = [];
        foreach ($this->variables_selected as $index=>$data) {
            $data[$index] = [];
            foreach ($array_aux as $item) {
                $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                array_push($data[$index], round($raw_json[$data['variable_name']], 2));
            }
            $this->series[$index] = ["name" => $data['variable_name'], "type"=>$this->chart_type, "data"=> $data[$index]];
        }
        $this->emit('changeAxis', ['series' => $this->series,  'x_axis'=>$this->x_axis]);
    }

    public function render()
    {
        return view('livewire.v1.monitoring.charts.line-chart');
    }
}
