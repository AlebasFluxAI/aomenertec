<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\RealTimeListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Matrix\Builder;
use PhpMqtt\Client\Facades\MQTT;

class DataChart extends Component
{
    public $client;
    public $variables;
    public $variables_selected;
    public $data_frame;
    public $data_chart;
    public $variable_chart_id;
    public $chart_type;
    public $time_id;
    public $series;
    public $x_axis;
    public $date_range;
    public $end;
    public $start;
    public $chart_title;
    protected $listeners = ['changeDateRange', 'selectHistory'];
    public function mount(Client $client, $variables, $data_frame, $data_chart, $time)
    {
        $this->client = $client;
        $this->variables = $variables;
        $this->data_frame = $data_frame;
        $this->variable_chart_id = 1;
        $this->variables_selected = $this->data_frame->where('variable_id', $this->variable_chart_id)->all();
        $aux = $variables->where('id', $this->variable_chart_id)->first();
        $this->chart_title = $aux['display_name'];
        $this->chart_type = $aux['chart_type'];
        $this->time_id = $time;
        $this->data_chart = $this->client->hourlyMicrocontrollerData()->orderBy('source_timestamp', 'desc')->limit(24)->get();
        if (count($this->data_chart)>0) {
            if ($time == 1 or $time == 2) {
                $this->end = $this->data_chart->first()->source_timestamp;
                $this->start = $this->data_chart->last()->source_timestamp;
            } else {
                $this->start = $this->data_chart->first()->microcontrollerData->source_timestamp;
                $this->end = $this->data_chart->last()->microcontrollerData->source_timestamp;
            }
            $this->date_range = $this->start . " - " . $this->end;
        }
        $this->chartRender(true);
    }

    public function restartDateRange()
    {
        if ($this->time_id == 1) {
            $this->data_chart = $this->client->microcontrollerData()->orderBy('source_timestamp', 'desc')->limit(60)->get();
        } elseif ($this->time_id == 2) {
            $this->data_chart = $this->client->hourlyMicrocontrollerData()->orderBy('source_timestamp', 'desc')->limit(24)->get();
        } elseif ($this->time_id == 3) {
            $this->data_chart = $this->client->dailyMicrocontrollerData()->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')->limit(31)->get();
        } else {
            $this->data_chart = $this->client->monthlyMicrocontrollerData()->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')->limit(12)->get();
        }
        if ($this->time_id == 1) {
            $this->end = $this->data_chart->first()->source_timestamp;
            $this->start = $this->data_chart->last()->source_timestamp;
        } else {
            $this->end = $this->data_chart->first()->microcontrollerData->source_timestamp;
            $this->start = $this->data_chart->last()->microcontrollerData->source_timestamp;
        }
        $this->date_range = $this->start . " - " . $this->end;
        $this->chartRender(true);
    }

    public function selectHistory()
    {
        if($this->client->clientConfiguration()->first()->active_real_time) {
            if ($this->client->clientConfiguration()->first()->real_time_flag) {
                $equipment = $this->client->equipments()->whereEquipmentTypeId(1)->first();
                if (RealTimeListener::whereUserId(Auth::user()->id)
                    ->whereEquipmentId($equipment->id)->exists()) {
                    RealTimeListener::whereUserId(Auth::user()->id)
                        ->whereEquipmentId(
                            $equipment->id
                        )->delete();
                    if (!RealTimeListener::whereEquipmentId($equipment->id)->exists()) {
                        $message = "{'did':" . $equipment->serial . ",'realTimeFlag':false}";
                        $topic = 'mc/config/' . $equipment->serial;
                        MQTT::publish($topic, $message);
                        MQTT::disconnect();
                    }
                }
            }
        }
        $this->restartDateRange();
    }

    public function changeDateRange($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
        $this->date_range = $this->start . " - " . $this->end;
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
            $data_chart = $this->data_chart;
        } else {
            if ($this->time_id == 1) {
                $data_chart = $this->client->microcontrollerData()
                    ->whereBetween("source_timestamp", [$this->start, $this->end])
                    ->orderBy('source_timestamp', 'desc')
                    ->limit(250)->get();
            } elseif ($this->time_id == 2) {
                $data_chart = $this->client->hourlyMicrocontrollerData()
                    ->whereBetween("source_timestamp", [$this->start, $this->end])
                    ->orderBy('source_timestamp', 'desc')
                    ->limit(250)->get();
            } elseif ($this->time_id == 3) {
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start, $this->end])->orderBy('source_timestamp', 'desc');
                    })->limit(250)->get();
            } else {
                $data_chart = $this->client->monthlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start, $this->end])->orderBy('source_timestamp', 'desc');
                    })->limit(250)->get();
            }
            $this->data_chart = $data_chart;
        }
        if (count($data_chart)>0) {
            if ($this->time_id == 1 or $this->time_id == 2) {
                $this->end = $this->data_chart->first()->source_timestamp;
                $this->start = $this->data_chart->last()->source_timestamp;
            } else {
                $this->end = $this->data_chart->first()->microcontrollerData->source_timestamp;
                $this->start = $this->data_chart->last()->microcontrollerData->source_timestamp;
            }
            $this->date_range = $this->start . " - " . $this->end;
            $array_aux = $data_chart->reverse();
            $this->series = [];
            $data_aux = [];
            $this->x_axis = [];
            $index = 0;
            foreach ($this->variables_selected as $data) {
                $data_aux[$index] = [];
                foreach ($array_aux as $item) {
                    if ($this->time_id == 3 || $this->time_id == 4) {
                        $raw_json = json_decode($item->raw_json, true);
                        if (isset($raw_json[$data['variable_name']])) {
                            array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                        } else {
                            array_push($data_aux[$index], null);
                        }
                    } elseif ($this->time_id == 2) {
                        $raw_json = json_decode($item->raw_json, true);
                        if (isset($raw_json[$data['variable_name']])) {
                            array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                        } else {
                            array_push($data_aux[$index], null);
                        }
                    } else {
                        $raw_json = json_decode($item->raw_json, true);
                        if (isset($raw_json[$data['variable_name']])) {
                            array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                        } else {
                            array_push($data_aux[$index], null);
                        }
                    }
                    if ($index == 0) {
                        if ($this->time_id == 1) {
                            $x = Carbon::create($item->source_timestamp)->format('d F H:i:s');
                        } elseif ($this->time_id == 2) {
                            $x = Carbon::create($item->year, $item->month, $item->day, $item->hour)->format('d F H:00');
                        } elseif ($this->time_id == 3) {
                            $x = Carbon::create($item->year, $item->month, $item->day)->format('d F Y');
                        } else {
                            $x = Carbon::create($item->year, $item->month, $item->day)->format('d F Y');
                        }
                        array_push($this->x_axis, $x);
                    }
                }
                $this->series[$index] = ["name" => $data['display_name'], "type" => $this->chart_type, "data" => $data_aux[$index]];
                $index++;
            }
            $this->emit('changeAxis', ['series' => $this->series, 'x_axis' => $this->x_axis, 'title' => $this->chart_title]);
        } else {
            $this->emit('changeAxis', ['series' => [], 'x_axis' => [], 'title' => $this->chart_title]);
        }
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.data-chart');
    }
}
