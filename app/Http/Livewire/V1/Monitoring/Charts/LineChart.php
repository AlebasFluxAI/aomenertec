<?php

namespace App\Http\Livewire\V1\Monitoring\Charts;

use App\Models\V1\Client;
use Livewire\Component;

class LineChart extends Component
{
    protected $listeners = ['changeVariable', 'changeTime', 'changeDateRange', 'startDateRange'];
    public $data;
    public $x_axis;
    public $L1;
    public $L2;
    public $L3;
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
        $this->L1 = [];
        $this->L2 = [];
        $this->L3 = [];
        $this->x_axis = [];
        $this->client =  $client;
        $this->data_chart = $data_chart;
        $this->end = $this->data_chart->last()->microcontrollerData->source_timestamp;
        $this->start = $this->data_chart->first()->microcontrollerData->source_timestamp;
        $this->variables_selected =$variables_selected;
        $array_aux = $this->data_chart->reverse();
        foreach ($array_aux as $item) {
            $raw_json = json_decode($item->microcontrollerData->raw_json, true);
            foreach ($this->variables_selected as $index=>$data) {
                if ($index == 0) {
                    array_push($this->L1, round($raw_json[$data['variable_name']], 2));
                } elseif ($index == 1) {
                    array_push($this->L2, round($raw_json[$data['variable_name']], 2));
                } elseif ($index == 2) {
                    array_push($this->L3, round($raw_json[$data['variable_name']], 2));
                }
            }
            array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
        }
    }
    public function startDateRange(){
        $this->L1 = [];
        $this->L2 = [];
        $this->L3 = [];
        $this->x_axis = [];
        if ($this->time == 1) {
            $this->data_chart = $this->client->hourlyMicrocontrollerData()->limit(60)->get();
        } elseif ($this->time == 2) {
            $this->data_chart = $this->client->dailyMicrocontrollerData()->limit(24)->get();
        } elseif ($this->time == 3) {
            $this->data_chart = $this->client->monthlyMicrocontrollerData()->limit(31)->get();
        } else {
            $this->data_chart = $this->client->annualMicrocontrollerData()->limit(12)->get();
        }
        $array_aux = $this->data_chart->reverse();
        foreach ($array_aux as $item) {
            $raw_json = json_decode($item->microcontrollerData->raw_json, true);
            foreach ($this->variables_selected as $index=>$data) {
                if ($index == 0) {
                    array_push($this->L1, round($raw_json[$data['variable_name']], 2));
                } elseif ($index == 1) {
                    array_push($this->L2, round($raw_json[$data['variable_name']], 2));
                } elseif ($index == 2) {
                    array_push($this->L3, round($raw_json[$data['variable_name']], 2));
                }
            }
            array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
        }
        $this->emit('changeAxis', ['variables' => $this->variables_selected, 'L1' => $this->L1, 'L2' => $this->L2, 'L3' => $this->L3, 'x_axis'=>$this->x_axis]);

    }
    public function changeDateRange($start, $end){
        $this->start = $start;
        $this->end = $end;
        if ($this->time == 1) {
            $this->data_chart = $this->client->hourlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start, $this->end])->get();
        } elseif ($this->time == 2) {
            $this->data_chart = $this->client->dailyMicrocontrollerData()
            ->whereBetween("created_at", [$this->start, $this->end])->get();
        } elseif ($this->time == 3) {
            $this->data_chart = $this->client->monthlyMicrocontrollerData()
            ->whereBetween("created_at", [$this->start, $this->end])->get();
        } else {
            $this->data_chart = $this->client->annualMicrocontrollerData()
                ->whereBetween("created_at", [$this->start, $this->end])->get();
        }
        $this->L1 = [];
        $this->L2 = [];
        $this->L3 = [];
        $this->x_axis = [];
        $array_aux = $this->data_chart->reverse();
        foreach ($array_aux as $item) {
            $raw_json = json_decode($item->microcontrollerData->raw_json, true);
            foreach ($this->variables_selected as $index=>$data) {
                if ($index == 0) {
                    array_push($this->L1, round($raw_json[$data['variable_name']], 2));
                } elseif ($index == 1) {
                    array_push($this->L2, round($raw_json[$data['variable_name']], 2));
                } elseif ($index == 2) {
                    array_push($this->L3, round($raw_json[$data['variable_name']], 2));
                }
            }
            array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
        }

        $this->emit('changeAxis', ['variables' => $this->variables_selected, 'L1' => $this->L1, 'L2' => $this->L2, 'L3' => $this->L3, 'x_axis'=>$this->x_axis]);
    }
    public function changeTime($time)
    {
        $this->time = $time;
        if ($time == 1) {
            $this->data_chart = $this->client->hourlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start, $this->end])->get();
        } elseif ($time == 2) {
            $this->data_chart = $this->client->dailyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start, $this->end])->get();
        } elseif ($time == 3) {
            $this->data_chart = $this->client->monthlyMicrocontrollerData()
                ->whereBetween("created_at", [$this->start, $this->end])->get();
        } else {
            $this->data_chart = $this->client->annualMicrocontrollerData()
                            ->whereBetween("created_at", [$this->start, $this->end])->get();
        }

        $this->L1 = [];
        $this->L2 = [];
        $this->L3 = [];
        $this->x_axis = [];
        $array_aux = $this->data_chart->reverse();
        foreach ($array_aux as $item) {
            $raw_json = json_decode($item->microcontrollerData->raw_json, true);
            foreach ($this->variables_selected as $index=>$data) {
                if ($index == 0) {
                    array_push($this->L1, round($raw_json[$data['variable_name']], 2));
                } elseif ($index == 1) {
                    array_push($this->L2, round($raw_json[$data['variable_name']], 2));
                } elseif ($index == 2) {
                    array_push($this->L3, round($raw_json[$data['variable_name']], 2));
                }
            }
            array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
        }

        $this->emit('changeAxis', ['variables' => $this->variables_selected, 'L1' => $this->L1, 'L2' => $this->L2, 'L3' => $this->L3, 'x_axis'=>$this->x_axis]);
    }

    public function changeVariable($variables, $chart_type)
    {
        $this->chart_type = $chart_type;
        $this->variables_selected = $variables;
        $this->L1 = [];
        $this->L2 = [];
        $this->L3 = [];
        $this->x_axis = [];
        $array_aux = $this->data_chart->reverse();
        foreach ($array_aux as $item) {
            $raw_json = json_decode($item->microcontrollerData->raw_json, true);
            foreach ($this->variables_selected as $index=>$data) {
                if ($index == 0) {
                    array_push($this->L1, round($raw_json[$data['variable_name']], 2));
                } elseif ($index == 1) {
                    array_push($this->L2, round($raw_json[$data['variable_name']], 2));
                } elseif ($index == 2) {
                    array_push($this->L3, round($raw_json[$data['variable_name']], 2));
                }
            }
            array_push($this->x_axis, $item->microcontrollerData->source_timestamp);
        }

        $this->emit('changeAxis', ['variables' => $this->variables_selected, 'L1' => $this->L1, 'L2' => $this->L2, 'L3' => $this->L3, 'x_axis'=>$this->x_axis, 'chart_type'=>$this->chart_type]);
    }

    public function render()
    {
        return view('livewire.v1.monitoring.charts.line-chart');
    }
}
