<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Models\V1\Client;
use Livewire\Component;

class CardsData extends Component
{
    public $variables_selected;
    public $variables;
    public $data_frame;
    public $client;
    public $last_data;
    public $cards;

    protected $rules = [

        'cards.*.color' => 'required',
        'cards.*.id' => 'required',
        'cards.*.icon' => 'required',
        'cards.*.list_model_variable' => 'required',
        'cards.*.variables_selected' => 'required',
    ];

    public function mount(Client $client, $variables, $data_frame)
    {
        $this->variables = $variables;
        $this->data_frame = $data_frame;
        $this->client =  $client;
        $last_data = $this->client->microcontrollerData()->latest()->first();
        $this->last_data = collect(json_decode($last_data->raw_json, true));
        $this->cards = [];
        $this->variables_selected = [];
        $initial_variables = $variables->whereIn('id', [1, 9, 13, 17, 18, 19]);
        foreach ($initial_variables as $variable) {
            $aux = [];
            $var_data_frame = $this->data_frame->where('variable_id', $variable['id'])->all();
            foreach ($var_data_frame as $item) {
                $item['value'] = round($this->last_data[$item['variable_name']], 2);
                array_push($aux, $item);
            }
            array_push($this->cards, [
                "id" => $variable['id'],
                "color" => $variable['style'],
                "icon" => $variable['icon'],
                "list_model_variable" => $variable['id'],
                "variables_selected" => $aux,
            ]);
        }

    }

    public function updated($property_name, $value)
    {
        if (strpos($property_name, "cards") !== false) {
            $variable_select = $this->variables->where('id', $value)->first();
            $id = filter_var($property_name, FILTER_SANITIZE_NUMBER_INT);
            $aux = [];
            $var_data_frame = $this->data_frame->where('variable_id', $value)->all();
            foreach ($var_data_frame as $item) {
                $item['value'] = round($this->last_data[$item['variable_name']], 2);
                array_push($aux, $item);
            }
            $this->cards[$id] = [
                            'id' => $variable_select['id'],
                            'color' => $variable_select['style'],
                            'icon' => $variable_select['icon'],
                            'list_model_variable' => $variable_select['id'],
                            'variables_selected' => $aux
                        ];
        }
    }

    /*public function startDateRange()
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
    }*/

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.cards-data');
    }
}
