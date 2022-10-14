<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpMqtt\Client\Facades\MQTT;

class ReactiveChart extends Component
{
    public $client;
    public $time_reactive_id;
    public $date_range_reactive;
    public $series_reactive;
    public $x_axis_reactive;
    public $reactive_variables;
    public $data_chart_reactive;
    public $start_reactive;
    public $end_reactive;
    public $penalizable;
    public $inductive_filter;
    public $capacitive_filter;
    protected $listeners = ['selectReactive', 'dateRangeReactive'];

    public function mount(Client $client, $reactive_variables, $data_chart_reactive, $time)
    {
        $this->client = $client;
        $edit_index = [];
        $i = 0;
        foreach ($reactive_variables as $data) {
            $edit_index[$i] = $data;
            $i++;
        }
        $this->penalizable = false;
        $this->reactive_variables = $edit_index;
        $this->time_reactive_id = $time;
        $this->data_chart_reactive = $data_chart_reactive;
        $this->series_reactive = [];
        $this->x_axis_reactive = [];
        $this->capacitive_filter = 0;
        $this->inductive_filter = 0;
    }

    public function queryData()
    {
        if ($this->time_reactive_id == 1) {
            $data_chart = $this->client->microcontrollerData()
                ->whereBetween("source_timestamp", [$this->start_reactive, $this->end_reactive])
                ->orderBy('source_timestamp', 'desc')
                ->limit(60)->get();
        } elseif ($this->time_reactive_id == 2) {
            if ($this->penalizable) {
                $data_chart = $this->client->hourlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start_reactive, $this->end_reactive]);
                    })
                    ->where('penalizable_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('penalizable_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(60)->get();
            } else {
                $data_chart = $this->client->hourlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start_reactive, $this->end_reactive]);
                    })
                    ->where('interval_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('interval_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(60)->get();
            }
        } elseif ($this->time_reactive_id == 3) {
            if ($this->penalizable) {
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start_reactive, $this->end_reactive]);
                    })
                    ->where('penalizable_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('penalizable_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(60)->get();
            } else {
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start_reactive, $this->end_reactive]);
                    })
                    ->where('interval_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('interval_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(60)->get();
            }
        } else {
            if ($this->penalizable) {
                $data_chart = $this->client->monthlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start_reactive, $this->end_reactive]);
                    })
                    ->where('penalizable_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('penalizable_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(60)->get();
            } else {
                $data_chart = $this->client->monthlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start_reactive, $this->end_reactive]);
                    })
                    ->where('interval_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('interval_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(60)->get();
            }
        }
        if (count($data_chart)>0) {
            $this->data_chart_reactive = $data_chart;
            if ($this->time_reactive_id == 1) {
                $this->end_reactive = $this->data_chart_reactive->first()->source_timestamp;
                $this->start_reactive = $this->data_chart_reactive->last()->source_timestamp;
            } else {
                $this->end_reactive = $this->data_chart_reactive->first()->microcontrollerData->source_timestamp;
                $this->start_reactive = $this->data_chart_reactive->last()->microcontrollerData->source_timestamp;
            }
            $this->date_range_reactive = $this->start_reactive . " - " . $this->end_reactive;
        }
        return $data_chart;
    }

    public function selectReactive()
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
        if ($this->time_reactive_id == 1) {
            $data_chart = $this->client->microcontrollerData()->orderBy('source_timestamp', 'desc')->limit(60)->get();
        } elseif ($this->time_reactive_id == 2) {
            if ($this->penalizable) {
                $data_chart = $this->client->hourlyMicrocontrollerData()
                    ->where('penalizable_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('penalizable_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(24)->get();
            } else {
                $data_chart = $this->client->hourlyMicrocontrollerData()
                    ->where('interval_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('interval_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(24)->get();
            }
        } elseif ($this->time_reactive_id == 3) {
            if ($this->penalizable) {
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->where('penalizable_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('penalizable_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(31)->get();
            } else {
                $data_chart = $this->client->dailyMicrocontrollerData()
                    ->where('interval_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('interval_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(31)->get();
            }
        } else {
            if ($this->penalizable) {
                $data_chart = $this->client->monthlyMicrocontrollerData()
                    ->where('penalizable_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('penalizable_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(12)->get();
            } else {
                $data_chart = $this->client->monthlyMicrocontrollerData()
                    ->where('interval_reactive_inductive_consumption', '>=', $this->inductive_filter)
                    ->where('interval_reactive_capacitive_consumption', '>=', $this->capacitive_filter)
                    ->limit(12)->get();
            }
        }

        $this->data_chart_reactive = $data_chart;
        if ($this->time_reactive_id == 1) {
            $this->end_reactive = $this->data_chart_reactive->first()->source_timestamp;
            $this->start_reactive = $this->data_chart_reactive->last()->source_timestamp;
        } else {
            $this->end_reactive = $this->data_chart_reactive->first()->microcontrollerData->source_timestamp;
            $this->start_reactive = $this->data_chart_reactive->last()->microcontrollerData->source_timestamp;
        }
        $this->date_range_reactive = $this->start_reactive . " - " . $this->end_reactive;
        $this->chartRender(true);
    }

    public function updatedPenalizable()
    {
        if ($this->penalizable) {
            if ($this->time_reactive_id == 1) {
                $this->time_reactive_id = 2;
            }
            $this->data_chart_reactive = $this->queryData();
        }
        $this->chartRender($this->penalizable);
    }

    public function applyFilterReactive()
    {
        if ($this->time_reactive_id != 1) {
            $this->chartRender(false);
        }
    }

    private function chartRender($flag)
    {
        if (!$flag) {
            $this->data_chart_reactive = $this->queryData();
        }
        if (count($this->data_chart_reactive)>0) {
            $array_aux = $this->data_chart_reactive->reverse();
            $this->series_reactive = [];
            $data_aux = [];
            $this->x_axis_reactive = [];
            foreach ($this->reactive_variables as $index => $data) {
                $data_aux[$index] = [];
                foreach ($array_aux as $item) {
                    if ($this->penalizable) {
                        if ($data['variable_name'] == "kwh_interval") {
                            array_push($data_aux[$index], round($item->interval_real_consumption, 2));
                        } elseif ($data['variable_name'] == "varLh_interval") {
                            array_push($data_aux[$index], round($item->penalizable_reactive_inductive_consumption, 2));
                        } else {
                            array_push($data_aux[$index], round($item->penalizable_reactive_capacitive_consumption, 2));
                        }
                    } else {
                        if ($this->time_reactive_id == 3 || $this->time_reactive_id == 4) {
                            $raw_json = json_decode($item->raw_json, true);
                            array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                        } elseif ($this->time_reactive_id == 2) {
                            $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                            array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                        } else {
                            $raw_json = json_decode($item->raw_json, true);
                            array_push($data_aux[$index], round($raw_json[$data['variable_name']], 2));
                        }
                    }
                    if ($index == 0) {
                        if ($this->time_reactive_id == 1) {
                            $x = Carbon::create($item->source_timestamp)->format('d F H:i');
                        } elseif ($this->time_reactive_id == 2) {
                            $x = Carbon::create($item->year, $item->month, $item->day, $item->hour)->format('d F H:00');
                        } elseif ($this->time_reactive_id == 3) {
                            $x = Carbon::create($item->year, $item->month, $item->day)->format('d F Y');
                        } else {
                            $x = Carbon::create($item->year, $item->month, $item->day)->format('d F Y');
                        }
                        array_push($this->x_axis_reactive, $x);
                    }
                }
                $this->series_reactive[$index] = ["name" => $data['display_name'], "data" => $data_aux[$index]];
            }
            $this->emit('changeAxisReactive', ['series_reactive' => $this->series_reactive, 'x_axis_reactive' => $this->x_axis_reactive]);
        } else {
            $this->emit('changeAxisReactive', ['series_reactive' => [], 'x_axis_reactive' => []]);
        }
    }

    public function updatedTimeReactiveId()
    {
        if ($this->time_reactive_id == 1) {
            $this->penalizable = false;
            $this->inductive_filter = 0;
            $this->capacitive_filter = 0;
        }
        $this->chartRender(false);
    }

    public function dateRangeReactive($start, $end)
    {
        $this->date_range_reactive = $start . " - " . $end;
        $this->start_reactive = $start;
        $this->end_reactive = $end;
        $this->chartRender(false);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.reactive-chart');
    }
}
