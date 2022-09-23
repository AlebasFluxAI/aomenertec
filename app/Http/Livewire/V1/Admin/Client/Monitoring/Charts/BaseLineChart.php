<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpMqtt\Client\Facades\MQTT;

class BaseLineChart extends Component
{public $client;
    public $variables;
    public $variables_selected;
    public $data_frame;
    public $data_chart_result;
    public $data_chart_reference;
    public $variable_chart_id;
    public $chart_type;
    public $time_id;
    public $series;
    public $x_axis;
    public $date_range_reference;
    public $date_range_result;
    public $end_reference;
    public $start_reference;
    public $end_result;
    public $start_result;
    public $chart_title;
    protected $listeners = ['changeDateRangeReference', 'changeDateRangeResult', 'selectHistory'];
    public function mount(Client $client, $variables, $data_frame, $data_chart, $time)
    {

        $this->client = $client;
        $this->variables = $variables;
        $this->data_frame = $data_frame;
        $this->variable_chart_id = 2;
        $this->variables_selected = $this->data_frame->where('variable_id', $this->variable_chart_id)->all();
        $aux = $variables->where('id', $this->variable_chart_id)->first();
        $this->chart_title = $aux['display_name'];
        $this->chart_type = 'line';
        $this->time_id = 2;
        $this->data_chart_result = $this->data_chart = $this->client->hourlyMicrocontrollerData()->limit(24)->get();;
        $this->end_result = $data_chart->first()->microcontrollerData->source_timestamp;
        $this->start_result = $data_chart->last()->microcontrollerData->source_timestamp;
        $this->date_range_result = $this->start_result . " - " . $this->end_result;

        $end_reference = Carbon::create($this->end_result);
        $this->end_reference = $end_reference->subDays(6);
        $start_reference = Carbon::create($this->start_result);
        $this->start_reference = $start_reference->subDays(6);
        $this->date_range_reference = $this->start_reference . " - " . $this->end_reference;
        $this->chartRender(true);
    }

    public function restartDateRange()
    {
        if ($this->time_id == 2) {
            $this->data_chart_result = $this->client->hourlyMicrocontrollerData()->limit(24)->get();
        } elseif ($this->time_id == 3) {
            $this->data_chart_result = $this->client->dailyMicrocontrollerData()->limit(31)->get();
        } else {
            $this->data_chart_result = $this->client->monthlyMicrocontrollerData()->limit(12)->get();
        }
        $this->end_result = $this->data_chart_result->first()->microcontrollerData->source_timestamp;
        $this->start_result = $this->data_chart_result->last()->microcontrollerData->source_timestamp;
        $this->date_range_result = $this->start_result . " - " . $this->end_result;
        $end_reference = Carbon::create($this->end_result);
        $this->end_reference = $end_reference->subDays(6);
        $start_reference = Carbon::create($this->start_result);
        $this->start_reference = $start_reference->subDays(6);
        $this->date_range_reference = $this->start_reference . " - " . $this->end_reference;
        $this->chartRender(true);
    }

    public function selectHistory()
    {
        $equipment =$this->client->equipments()->whereEquipmentTypeId(1)->first();
        RealTimeListener::whereUserId(Auth::user()->id)
            ->whereEquipmentId(
                $equipment->id
            )->delete();

        if (!RealTimeListener::whereEquipmentId(
            $equipment->id
        )->exists()) {
            $message = "{'did':" . $equipment->serial . ",'realTimeFlag':false}";
            $topic = 'mc/config/'.$equipment->serial;
            MQTT::publish($topic, $message);
            MQTT::disconnect();
        }
        $this->restartDateRange();
    }

    public function changeDateRangeResult($start, $end)
    {
        $this->start_result = $start;
        $this->end_result = $end;
        $this->date_range_result = $this->start_result . " - " . $this->end_result;
        $this->chartRender(false);
    }

    public function changeDateRangeReference($start, $end)
    {
        $this->start_reference = $start;
        $this->end_reference = $end;
        $this->date_range_reference = $this->start_reference . " - " . $this->end_reference;
        $this->chartRender(false);
    }

    public function updatedTimeId()
    {
        $this->chartRender(false);
    }

    public function updatedVariableChartId()
    {
        $variable = $this->variables->where('id', $this->variable_chart_id)->first();
        $this->chart_type = $variable['chart_type'];
        $this->chart_title = $variable['display_name'];
        $this->variables_selected = $this->data_frame->where('variable_id', $this->variable_chart_id);
        $this->chartRender(false);
    }



    private function chartRender($flag)
    {
        if ($flag) {
            $data_chart_result = $this->data_chart_result;
        } else {
            if ($this->time_id == 2) {
                $data_chart_result = $this->client->hourlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start_result, $this->end_result]);
                    })->limit(600)->get();
            } elseif ($this->time_id == 3) {
                $data_chart_result = $this->client->dailyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start_result, $this->end_result]);
                    })->limit(600)->get();
            } else {
                $data_chart_result = $this->client->monthlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start_result, $this->end_result]);
                    })->limit(600)->get();
            }
            $this->data_chart_result = $data_chart_result;
        }
        if (count($data_chart_result)>0) {
            if ($this->time_id == 1){
                $this->end_result = $this->data_chart_result->first()->source_timestamp;
                $this->start_result = $this->data_chart_result->last()->source_timestamp;
            } else {
                $this->end_result = $this->data_chart_result->first()->microcontrollerData->source_timestamp;
                $this->start_result = $this->data_chart_result->last()->microcontrollerData->source_timestamp;
            }
            $date_1 = Carbon::create(Carbon::create($this->end_result)->format('Y-m-d'));
            $date_2 = Carbon::create(Carbon::create($this->end_reference)->format('Y-m-d'));
            $diff_days =$date_1->diffInDays($date_2);
            $this->date_range_result = $this->start_result . " - " . $this->end_result;
            $array_aux_result = $data_chart_result->reverse();
            $this->series = [];
            $item_consumption = 0;
            $data_2_consumption = 0;
            $data_aux = [];
            $data_aux_2 = [];
            $this->x_axis = [];
            $index = 0;
            foreach ($this->variables_selected as $data) {
                $data_aux[$index] = [];
                $data_aux_2[$index] = [];
                foreach ($array_aux_result as $item) {
                    $item_date = Carbon::create($item->year, $item->month, $item->day)->subDays($diff_days);
                    if ($this->time_id == 2) {
                        $data_2 = $this->client->hourlyMicrocontrollerData()
                            ->where('year', $item_date->format('Y'),)
                            ->where('month', $item_date->format('m'),)
                            ->where('day', $item_date->format('d'),)
                            ->where('hour', $item->hour,)
                            ->first();
                    } elseif ($this->time_id == 3) {
                        $data_2 = $this->client->hourlyMicrocontrollerData()
                            ->where('year', $item_date->format('Y'),)
                            ->where('month', $item_date->format('m'),)
                            ->where('day', $item->day->format('d'),)
                            ->first();
                    } else {
                        $data_2 = $this->client->hourlyMicrocontrollerData()
                            ->where('year', $item_date->format('Y'),)
                            ->where('month', $item_date->format('m'),)
                            ->first();
                    }
                    if ($this->time_id == 3 || $this->time_id == 4) {
                        $raw_json = json_decode($item->raw_json, true);
                        array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                        $item_consumption = $item_consumption + round($raw_json[$data['variable_name']], 2);
                        if ($data_2){
                            $raw_json_2 = json_decode($data_2->raw_json, true);
                            array_push($data_aux_2[$index], round($raw_json_2[$data['variable_name']], 2));
                            $data_2_consumption = $data_2_consumption + round($raw_json_2[$data['variable_name']], 2);
                        } else{
                            array_push($data_aux_2[$index], null);
                        }

                    } else{
                        $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                        array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                        $item_consumption = $item_consumption + round($raw_json[$data['variable_name']], 2);
                        if ($data_2){
                            if ($data_2->microcontrollerData) {
                                $raw_json_2 = json_decode($data_2->microcontrollerData->raw_json, true);
                                array_push($data_aux_2[$index], round($raw_json_2[$data['variable_name']], 2));
                                $data_2_consumption = $data_2_consumption + round($raw_json_2[$data['variable_name']], 2);

                            }else{
                                array_push($data_aux_2[$index], null);
                            }
                        } else{
                            array_push($data_aux_2[$index], null);
                        }
                        if ($index == 0) {
                            if ($this->time_id == 2) {
                                if ($data_2){
                                    if ($item->month == $data_2->month){
                                        $x = $data_2->day ." - ". Carbon::create($item->year, $item->month, $item->day, $item->hour)->format('d F H:00');
                                    } else{
                                        $x = Carbon::create($data_2->year, $data_2->month, $data_2->day, $data_2->hour)->format('d F') . " - ".Carbon::create($item->year, $item->month, $item->day, $item->hour)->format('d F H:00') ;
                                    }
                                }else{
                                    $x = Carbon::create($item->year, $item->month, $item->day, $item->hour)->format('d F H:00');
                                }

                            } elseif ($this->time_id == 3) {
                                if ($data_2){
                                    if ($item->month == $data_2->month){
                                        $x = $data_2->day ." - ". Carbon::create($item->year, $item->month, $item->day, $item->hour)->format('d F');
                                    } else{
                                        $x = Carbon::create($data_2->year, $data_2->month, $data_2->day, $data_2->hour)->format('d F') . " - ".Carbon::create($item->year, $item->month, $item->day)->format('d F') ;
                                    }
                                }else{
                                    $x = Carbon::create($item->year, $item->month, $item->day, $item->hour)->format('d F');
                                }
                            } else {
                                $x = Carbon::create($item->year, $item->month, $item->day)->format('Y-F');
                            }
                            array_push($this->x_axis, $x);
                        }

                    }
                }

                $this->series[$index] = ["name" => 'REFERENCIA', "type" => 'line', "data" => $data_aux_2[$index]];
                $index++;
                $this->series[$index] = ["name" => "COMPARACION", "type" => "line", "data" => $data_aux[$index-1]];
            }
            $this->emit('changeAxis', ['series' => $this->series, 'x_axis' => $this->x_axis, 'title' => $this->chart_title, 'reference_consumption'=> $data_2_consumption, 'result_consumption'=>$item_consumption]);
        } else {
            $this->emit('changeAxis', ['series' => [], 'x_axis' => [], 'title' => $this->chart_title]);
        }
    }
    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.base-line-chart');
    }
}
