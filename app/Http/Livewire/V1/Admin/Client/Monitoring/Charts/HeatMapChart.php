<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;


use Carbon\Carbon;
use Livewire\Component;
use App\Models\V1\Client;
class HeatMapChart extends Component
{
    protected $listeners = ['editAxisHeatMap', 'dateRangeHeatMap'];
    public $date_range_heat_map;
    public $start_heat_map;
    public $end_heat_map;
    public $series_heat_map;
    public $reactive_variables;
    public $client;
    public $data_chart_heat_map;
    public $variable_heat_map_id;

    public function mount(Client $client, $reactive_variables, $data_chart_heat_map){
        $this->client = $client;
        $edit_index = [];
        $i=0;
        foreach ($reactive_variables as $data){
            $edit_index[$i] = $data;
            $i++;
        }
        $this->reactive_variables = $edit_index;
        $this->variable_heat_map_id = 2;
        $this->data_chart_heat_map = $data_chart_heat_map;
        $this->series_heat_map = [];
    }

    public function dateRangeHeatMap($start, $end){
        $start_day = Carbon::create($start);
        $end_day = Carbon::create($end);
        $this->start_heat_map = $start_day->format('Y-m-d');
        $this->end_heat_map = $end_day->format('Y-m-d');
        $days = $end_day->diffInDays($start_day);
        $max_value = 0;
        $this->series_heat_map = [];
        for ($i=0; $i<=$days; $i++){
            if ($i == 0){
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->whereDate('created_at', ($end_day->format('Y-m-d')))->get();
            } else{
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->whereDate('created_at', ($end_day->subDay(1)->format('Y-m-d')))->get();
            }
            if (count($data_chart)>0) {
                $array_aux = $data_chart;
                $data_aux = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                $name = "";
                foreach ($this->reactive_variables as $data) {
                    if ($data['variable_id'] == $this->variable_heat_map_id) {
                        foreach ($array_aux as $index => $item) {
                            $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                            $value = round($raw_json[$data['variable_name']], 2);
                            $data_aux[intval($item->hour)] = $value;
                            if ($value > $max_value){
                                $max_value = $value;
                            }
                            if ($index == 0) {
                                $name = new Carbon($item->microcontrollerData->source_timestamp);
                            }
                        }
                        $this->series_heat_map[$i] = ["name" => $name->toFormattedDateString(), "data" => $data_aux];
                    }
                }
            }
        }

        $this->emit('changeAxisHeatMap', ['series_heat_map' => $this->series_heat_map, 'max_value' => $max_value]);
    }

    public function updatedVariableHeatMapId(){

        $start_day = Carbon::create($this->start_heat_map);
        $end_day = Carbon::create($this->end_heat_map);
        $days = $end_day->diffInDays($start_day);
        $max_value = 0;
        $this->series_heat_map = [];
        for ($i=0; $i<=$days; $i++){
            if ($i == 0){
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->whereDate('created_at', ($end_day->format('Y-m-d')))->get();
            } else{
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->whereDate('created_at', ($end_day->subDay(1)->format('Y-m-d')))->get();
            }
            if (count($data_chart)>0) {
                $array_aux = $data_chart;
                $data_aux = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                $name = "";
                foreach ($this->reactive_variables as $data) {
                    if ($data['variable_id'] == $this->variable_heat_map_id) {
                        foreach ($array_aux as $index => $item) {
                            $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                            $value = round($raw_json[$data['variable_name']], 2);
                            $data_aux[intval($item->hour)] = $value;
                            if ($value > $max_value){
                                $max_value = $value;
                            }
                            if ($index == 0) {
                                $name = new Carbon($item->microcontrollerData->source_timestamp);
                            }
                        }
                        $this->series_heat_map[$i] = ["name" => $name->toFormattedDateString(), "data" => $data_aux];
                    }
                }
            }
        }
        $this->emit('changeAxisHeatMap', ['series_heat_map' => $this->series_heat_map, 'max_value' => $max_value]);
    }

    public function editAxisHeatMap(){
        $this->series_heat_map = [];
        $carbon = new Carbon();
        $days = 7;
        $max_value = 0;
        for ($i=0; $i<=$days; $i++){
            if ($i == 0){
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->whereDate('created_at', ($carbon->format('Y-m-d')))->get();
            } else{
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->whereDate('created_at', ($carbon->subDay(1)->format('Y-m-d')))->get();
            }
            if (count($data_chart)>0) {
                if ($i == 0){
                    $end = Carbon::create($data_chart->first()->microcontrollerData->source_timestamp);
                }
                $start = Carbon::create($data_chart->last()->microcontrollerData->source_timestamp);
                $array_aux = $data_chart;
                $data_aux = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                $name = "";
                foreach ($this->reactive_variables as $data) {
                    if ($data['variable_id'] == $this->variable_heat_map_id) {
                        foreach ($array_aux as $index => $item) {
                            $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                            $value = round($raw_json[$data['variable_name']], 2);
                            $data_aux[intval($item->hour)] = $value;
                            if ($value > $max_value){
                                $max_value = $value;
                            }
                            if ($index == 0) {
                                $name = new Carbon($item->microcontrollerData->source_timestamp);
                            }
                        }
                        $this->series_heat_map[$i] = ["name" => $name->toFormattedDateString(), "data" => $data_aux];
                    }
                }
            }
        }
        $this->start_heat_map = $start->format('Y-m-d');
        $this->end_heat_map = $end->format('Y-m-d');
        $this->date_range_heat_map = $this->start_heat_map." - ".$this->end_heat_map;
        $this->emit('changeAxisHeatMap', ['series_heat_map' => $this->series_heat_map, 'max_value' => $max_value]);
    }


    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.heat-map-chart');
    }
}
