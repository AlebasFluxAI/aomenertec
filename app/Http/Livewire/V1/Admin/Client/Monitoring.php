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
    public $reactive_variables;
    protected $listeners = ['echo:data-monitoring,.dataEventAdd' => 'addData1'];


    public function mount(Client $client)
    {
        $this->client = $client;
        $this->data_frame = collect(config('data-frame.data_frame'));
        $this->variables = collect(config('data-frame.variables'));
        $this->reactive_variables = $this->data_frame->whereIn('variable_id', [2, 14, 10])->toArray();
        $this->data_chart = $this->client->dailyMicrocontrollerData()->limit(24)->get();
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
