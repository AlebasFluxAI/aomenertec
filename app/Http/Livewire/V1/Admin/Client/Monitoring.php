<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Models\V1\Client;
use App\Models\V1\HourlyMicrocontrollerData;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Livewire\Component;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Illuminate\Support\Collection;

class Monitoring extends Component
{
    public $data;
    public $data_chart;
    public $last_data;
    public $data_frame;
    public $data_frame_collect;
    public $variables;
    public $variables_selected;
    public $cards;
    public $variable_chart_id;
    public $time_id;
    public $date_range;
    public $client;
    public $chart_type;
    public $variables_collect;
    protected $listeners = ['echo:data-monitoring,.dataEventAdd' => 'addData1', 'changeDateRange'];
    protected $rules = [

        'cards.*.color' => 'required',
        'cards.*.id' => 'required',
        'cards.*.icon' => 'required',
        'cards.*.list_model_variable' => 'required',
    ];

    public function mount(Client $client)
    {
        $this->client = $client;
        $last_data = $this->client->microcontrollerData->last();
        $this->last_data = json_decode($last_data->raw_json, true);

        $this->data_frame = config('data-frame.data_frame');
        $this->variables = config('data-frame.variables');
        $this->variables_collect = new Collection();
        foreach ($this->variables as $item) {
            $this->variables_collect->push((object)$item);
        }

        $this->cards = [];
        $this->variable_chart_id = 1;
        $this->variables_selected = [];
        foreach ($this->variables as $index => $variable) {
            $aux = [];
            foreach ($this->data_frame as $item) {
                if ($item['variable_id'] == $variable['id']) {
                    array_push($aux, $item);
                }
            }
            array_push($this->cards, [
                "id" => $variable['id'],
                "color" => $variable['style'],
                "icon" => $variable['icon'],
                "list_model_variable" => $variable['id'],
                "variables_selected" => $aux,
            ]);
            if ($index == 5) {
                break;
            }
        }
        foreach ($this->data_frame as $item) {
            if ($item['variable_id'] == $this->variable_chart_id) {
                array_push($this->variables_selected, $item);
            }
        }
        $this->chart_type = "line";
        $this->time_id = 1;
        if ($this->time_id == 1) {
            $this->data_chart = $client->hourlyMicrocontrollerData->take(60);

        } elseif ($this->time_id == 2) {
            $this->data_chart = $client->dailyMicrocontrollerData->take(24);
        } elseif ($this->time_id == 3) {
            $this->data_chart = $client->monthlyMicrocontrollerData->take(31);
        } else {
            $this->data_chart = $client->annualMicrocontrollerData->take(12);
        }
        $this->end = $this->data_chart->first()->microcontrollerData->source_timestamp;
        $this->start = $this->data_chart->last()->microcontrollerData->source_timestamp;
        $this->date_range = $this->start . " - " . $this->end;
    }

    public function changeDateRange($start, $end)
    {
        $this->date_range = $start . " - " . $end;
    }

    public function updatedVariableChartId()
    {

        foreach ($this->variables as $variable) {

            if ($variable['id'] == $this->variable_chart_id) {
                $this->chart_type = $variable['chart_type'];
            }

        }
        $this->variables_selected = [];
        foreach ($this->data_frame as $item) {
            if ($item['variable_id'] == $this->variable_chart_id) {
                array_push($this->variables_selected, $item);
            }
        }
        $this->emit('changeVariable', $this->variables_selected, $this->chart_type);
    }

    public function updatedTimeId()
    {
        $this->emit('changeTime', $this->time_id);
    }

    public function updated($property_name, $value)
    {
        if (strpos($property_name, "cards") !== false) {

            $variable_select = $this->variables_collect->where('id', $value)->first();
            $id = filter_var($property_name, FILTER_SANITIZE_NUMBER_INT);
            $aux = [];
            foreach ($this->data_frame as $item) {
                if ($item['variable_id'] == $value) {
                    array_push($aux, $item);
                }
            }
            $this->cards = array_replace($this->cards, [
                $id =>
                    ['id' => $variable_select['id'],
                        'color' => $variable_select['style'],
                        'icon' => $variable_select['icon'],
                        'list_model_variable' => $variable_select['id'],
                        'variables_selected' => $aux]
            ]);
        }
    }

    public function restartDateRange()
    {

        $this->L1 = [];
        $this->L2 = [];
        $this->L3 = [];
        $this->x_axis = [];
        if ($this->time_id == 1) {
            $this->data_chart = $this->client->hourlyMicrocontrollerData->take(60);
        } elseif ($this->time_id == 2) {
            $this->data_chart = $this->client->dailyMicrocontrollerData->take(24);
        } elseif ($this->time_id == 3) {
            $this->data_chart = $this->client->monthlyMicrocontrollerData->take(31);
        } else {
            $this->data_chart = $this->client->annualMicrocontrollerData->take(12);
        }

        $this->end = $this->data_chart->first()->microcontrollerData->source_timestamp;
        $this->start = $this->data_chart->last()->microcontrollerData->source_timestamp;
        $this->date_range = $this->start . " - " . $this->end;
        $this->emit('startDateRange');


    }

    /* public function getListeners()
     {
         return [
             "echo-private:real-time-monitoring.{$this->raw_json['client_id']},RealTimeMonitoringEvent" => 'newData',
         ];
     }*/
    /*public function newData($data){

    }*/
    public function addData1()
    {
        $last_data = Client::find(1)->microcontrollerData->last();
        $this->last_data = json_decode($last_data->raw_json, true);
        $data_frame_collect = new Collection();
        foreach ($this->data_frame as $item) {
            $data_frame_collect->push((object)$item);
        }
        $update_cards = new Collection();
        $update_cards = $this->cards;
        $this->cards = new Collection();
        foreach ($update_cards as $index => $variable) {
            $this->cards->push((object)[
                "id" => $variable['id'],
                "color" => $variable['color'],
                "icon" => $variable['icon'],
                "list_model_variable" => $variable['list_model_variable'],
                "variables_selected" => $data_frame_collect->where('variable_id', $variable['id']),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring')
            ->extends('layouts.v1.app');

    }
}
