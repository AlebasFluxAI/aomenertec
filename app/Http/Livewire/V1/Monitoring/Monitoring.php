<?php

namespace App\Http\Livewire\V1\Monitoring;

use App\Models\V1\Client;
use App\Models\V1\HourlyMicrocontrollerData;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Livewire\Component;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Illuminate\Support\Collection;

class Monitoring extends Component
{
    protected $listeners = ['echo:data-monitoring,.dataEventAdd' => 'addData1', 'changeDateRange'];


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
    protected $rules = [

        'cards.*.color' => 'required',
        'cards.*.id' => 'required',
        'cards.*.icon' => 'required',
        'cards.*.list_model_variable' => 'required',
    ];
    public function mount()
    {
        $last_data = Client::find(2)->microcontrollerData->last();
        $this->last_data = json_decode($last_data->raw_json, true);
        $this->data_frame = config('data-frame.data_frame');
        $this->data_frame_collect = new Collection();
        $this->data_frame_collect = collect($this->data_frame);
        $this->variables = config('data-frame.variables');

        $this->cards = [];
        foreach ($this->variables as $index=>$variable) {
            $aux = [];
            foreach ($this->data_frame as $item){
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
            if (count($this->cards) == 6){
                break;
            }
        }
        $this->variable_chart_id = 1;
        $this->time_id = 1;
        $this->variables_selected = [];
        foreach ($this->data_frame as $item){
            if ($item['variable_id'] == $this->variable_chart_id) {
                array_push($this->variables_selected, $item);
            }
        }
    }

    public function changeDateRange($start, $end){
        $this->date_range = $start." - ".$end;
    }

    public function updatedVariableChartId(){
        $this->variables_selected = [];
        foreach ($this->data_frame as $item){
            if ($item['variable_id'] == $this->variable_chart_id) {
                array_push($this->variables_selected, $item);
            }
        }
        $this->emit('changeVariable', $this->variables_selected);
    }
    public function updatedTimeId(){
        $this->emit('changeTime', $this->time_id);
    }

    public function updated($property_name, $value){


        if (strpos($property_name, "cards") !== false){
            $variables = new Collection();
            foreach($this->variables as $item){
                $variables->push((object)$item);
            }
            $variable_select = $variables->where('id', $value)->first();
            $id = filter_var($property_name, FILTER_SANITIZE_NUMBER_INT);
            $data_frame_collect = new Collection();
            foreach($this->data_frame as $item){
                $data_frame_collect->push((object)$item);
            }
            $aux = [];
            foreach ($this->data_frame as $item){
                if ($item['variable_id'] == $value) {
                    array_push($aux, $item);
                }
            }
            $this->cards = array_replace($this->cards, [
                $id =>
                ['id' => $variable_select->id,
                 'color' => $variable_select->style,
                'icon' => $variable_select->icon,
                'list_model_variable' => $variable_select->id,
                'variables_selected' => $aux]
            ]);

            $last_data = Client::find(2)->microcontrollerData->last();
            $this->last_data = json_decode($last_data->raw_json, true);
        }

    }

   /* public function getListeners()
    {
        return [
            "echo-private:real-time-monitoring.{$this->raw_json['client_id']},RealTimeMonitoringEvent" => 'newData',
        ];
    }*/
    /*public function newData($data){

    }*/
    public function addData1(){
        $last_data = Client::find(2)->microcontrollerData->last();
        $this->last_data = json_decode($last_data->raw_json, true);
        $data_frame_collect = new Collection();
        foreach($this->data_frame as $item){
            $data_frame_collect->push((object)$item);
        }
        $update_cards = new Collection();
        $update_cards = $this->cards;
        $this->cards = new Collection();
        foreach ($update_cards as $index=>$variable) {
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

        return view('livewire.v1.monitoring.monitoring')
            ->extends('layouts.v1.app');
    }
}
