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
    public function mount(Client $client, $variables_selected, $time, $chart_type)
    {
        $this->time = $time;
        $this->chart_type = $chart_type;
        $this->L1 = [];
        $this->L2 = [];
        $this->L3 = [];
        $this->x_axis = [];
        $this->client =  $client;
        if ($time == 1) {
            $this->data_chart = $client->hourlyMicrocontrollerData->take(60);
        } elseif ($time == 2) {
            $this->data_chart = $client->dailyMicrocontrollerData->take(24);
        } elseif ($time == 3) {
            $this->data_chart = $client->monthlyMicrocontrollerData->take(31);
        } else {
            $this->data_chart = $client->annualMicrocontrollerData->take(12);
        }
        $this->end = $this->data_chart->first()->microcontrollerData->source_timestamp;
        $this->start = $this->data_chart->last()->microcontrollerData->source_timestamp;
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
            $this->data_chart = $this->client->hourlyMicrocontrollerData->take(60);
        } elseif ($this->time == 2) {
            $this->data_chart = $this->client->dailyMicrocontrollerData->take(24);
        } elseif ($this->time == 3) {
            $this->data_chart = $this->client->monthlyMicrocontrollerData->take(31);
        } else {
            $this->data_chart = $this->client->annualMicrocontrollerData->take(12);
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
            $this->data_chart = $this->client->hourlyMicrocontrollerData
                ->whereBetween("created_at", [$this->start, $this->end]);
        } elseif ($this->time == 2) {
            $this->data_chart = $this->client->dailyMicrocontrollerData
            ->whereBetween("created_at", [$this->start, $this->end]);
        } elseif ($this->time == 3) {
            $this->data_chart = $this->client->monthlyMicrocontrollerData
            ->whereBetween("created_at", [$this->start, $this->end]);
        } else {
            $this->data_chart = $this->client->annualMicrocontrollerData
                ->whereBetween("created_at", [$this->start, $this->end]);
        }
        $array_aux = $this->data_chart->reverse();
        $this->L1 = [];
        $this->L2 = [];
        $this->L3 = [];
        $this->x_axis = [];
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
            $this->data_chart = $this->client->hourlyMicrocontrollerData
                ->whereBetween("created_at", [$this->start, $this->end]);
        } elseif ($time == 2) {
            $this->data_chart = $this->client->dailyMicrocontrollerData->take(24);
        } elseif ($time == 3) {
            $this->data_chart = $this->client->monthlyMicrocontrollerData->take(31);
        } else {
            $this->data_chart = $this->client->annualMicrocontrollerData
                            ->whereBetween("created_at", [$this->start, $this->end]);
        }
        $array_aux = $this->data_chart->reverse();
        $this->L1 = [];
        $this->L2 = [];
        $this->L3 = [];
        $this->x_axis = [];
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
        if ($this->time == 1) {
            $this->data_chart = $this->client->hourlyMicrocontrollerData
                ->whereBetween("created_at", [$this->start, $this->end]);
        } elseif ($this->time == 2) {
            $this->data_chart = $this->client->dailyMicrocontrollerData->take(24);
        } elseif ($this->time == 3) {
            $this->data_chart = $this->client->monthlyMicrocontrollerData->take(31);
        } else {
            $this->data_chart = $this->client->annualMicrocontrollerData->take(12);
        }
        $this->variables_selected = $variables;
        $array_aux = $this->data_chart->reverse();
        $this->L1 = [];
        $this->L2 = [];
        $this->L3 = [];
        $this->x_axis = [];
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
