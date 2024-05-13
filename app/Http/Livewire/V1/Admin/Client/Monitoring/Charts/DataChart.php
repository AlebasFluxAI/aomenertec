<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use App\ModulesAux\MQTT;
use App\Strategy\MqttSenderPattern\FetchDataApiStrategy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpMqtt\Client\Exceptions\MqttClientException;

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
    public $select_data;
    protected $listeners = ['changeDateRange', 'selectHistory', 'setPointPhasor'];

    public function mount(Client $client, $variables, $data_frame, $data_chart, $time)
    {
        $this->select_data = false;
        $this->client = $client;
        $this->variables = $variables;
        $this->data_frame = $data_frame;
        $this->variable_chart_id = 1;
        $this->variables_selected = $this->data_frame->where('variable_id', $this->variable_chart_id)->all();
        $aux = $variables->where('id', $this->variable_chart_id)->first();
        $this->chart_title = $aux['display_name'];
        $this->chart_type = $aux['chart_type'];
        $this->time_id = $time;
        $this->series = [];
        $this->x_axis = [];
        $this->data_chart = $this->client->hourlyMicrocontrollerData()->orderBy('source_timestamp', 'desc')->limit(24)->get();
        if (count($this->data_chart) > 0) {
            if ($time == 1 or $time == 2) {
                $this->end = $this->data_chart->first()->source_timestamp;
                $this->start = $this->data_chart->last()->source_timestamp;
            } else {
                $this->start = $this->data_chart->first()->microcontrollerData->source_timestamp;
                $this->end = $this->data_chart->last()->microcontrollerData->source_timestamp;
            }
            $this->date_range = $this->start . " - " . $this->end;
        } else {
            $this->data_chart = [];
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
        if (count($this->data_chart)>0) {
            if ($this->time_id == 1) {
                $this->end = $this->data_chart->first()->source_timestamp;
                $this->start = $this->data_chart->last()->source_timestamp;
            } else {
                $this->end = $this->data_chart->first()->microcontrollerData->source_timestamp;
                $this->start = $this->data_chart->last()->microcontrollerData->source_timestamp;
            }
            $this->date_range = $this->start . " - " . $this->end;
        }
        $this->chartRender(true);
    }

    public function selectHistory()
    {
        if($this->client->clientConfiguration()->first()->active_real_time) {
                $equipment = $this->client->equipments()->whereEquipmentTypeId(7)->first();
                if (RealTimeListener::whereUserId(Auth::user()->id)
                    ->whereEquipmentId($equipment->id)->exists()) {
                    RealTimeListener::whereUserId(Auth::user()->id)
                        ->whereEquipmentId(
                            $equipment->id
                        )->forceDelete();
                    if (!RealTimeListener::whereEquipmentId($equipment->id)->exists()) {
                        $equipment= $this->client->equipments()->whereEquipmentTypeId(7)->first();
                        $apiKey =ApiKey::first();
                        $requestDetails = [
                            'url' => 'https://aom.enerteclatam.com/api/v1/config/set-status-real-time',
                            'method' => 'GET',
                            'body' => [
                                'serial' => $equipment->serial,
                                'status' => 0
                            ],
                            'apiKey' => $apiKey->api_key
                        ];
                        try {
                            $mqtt = MQTT::connection('default', EventLog::EVENT_ON_OFF_REAL_TIME.'-'.$equipment->serial.'-aom-channel');

                            $mqttCoilAckStrategy = new FetchDataApiStrategy($mqtt, $this);
                            $mqttCoilAckStrategy->fetchDataFromAPI($requestDetails);
                            $mqttCoilAckStrategy->registerLoopEventHandler();
                            $mqttCoilAckStrategy->subscribe($equipment, 18);
                        } catch (MqttClientException $e) {
                            $this->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Intente nuevamente"]);
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
                        $query->whereBetween("source_timestamp", [$this->start, $this->end]);
                    })
                    ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                    ->limit(250)->get();
            } else {
                $data_chart = $this->client->monthlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start, $this->end]);
                    })
                    ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                    ->limit(250)->get();
            }
            $this->data_chart = $data_chart;
        }

        if (count($data_chart) > 0) {
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
                            array_push($data_aux[$index], round($raw_json[$data['variable_name']], 4));
                        } else {
                            array_push($data_aux[$index], null);
                        }
                    } elseif ($this->time_id == 2) {
                        $raw_json = json_decode($item->raw_json, true);
                        if (isset($raw_json[$data['variable_name']])) {
                            array_push($data_aux[$index], round($raw_json[$data['variable_name']], 4));
                        } else {
                            array_push($data_aux[$index], null);
                        }
                    } else {
                        $raw_json = json_decode($item->raw_json, true);
                        if (isset($raw_json[$data['variable_name']])) {
                            array_push($data_aux[$index], round($raw_json[$data['variable_name']], 4));
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

                            if (is_numeric($item->day)) {
                                $x = Carbon::create($item->year, $item->month, $item->day)->format('d F Y');

                            } else {
                                $x = Carbon::create($item->day)->format('d F Y');

                            }
                        }
                        array_push($this->x_axis, $x);
                    }
                }
                $this->series[$index] = ["name" => $data['display_name'], "type" => $this->chart_type, "data" => $data_aux[$index]];
                $index++;
            }
            $this->emit('changeAxis', ['series' => $this->series, 'x_axis' => $this->x_axis, 'title' => $this->chart_title, 'type' => $this->chart_type]);
        } else {
            $this->emit('changeAxis', ['series' => [], 'x_axis' => [], 'title' => $this->chart_title]);
        }
    }

    public function setPointPhasor($point)
    {
        $data = $this->data_chart->reverse();
        $i = 0;
        foreach ($data as $datum) {
            if ($i == $point) {
                $json = json_decode($datum->raw_json, true);
                break;
            }
            $i++;
        }
        if ($json['total_phase_angle'] < 0) {
            $sum_angle_2 = -120;
            $sum_angle_3 = -240;
        } else {
            $sum_angle_2 = 240;
            $sum_angle_3 = 120;
        }
        $this->select_data = ['tittle' => 'phasor', 'lineFrecuency' => 60, 'samplesPerCycle' => 32, 'percent_volt' => ($json['ph1_ph2_volt'] == 0) ? 0 : round($json['ph2_ph3_volt'] / $json['ph1_ph2_volt'], 3), 'percent_curr' => ($json['ph1_current'] == 0) ? 0 : round($json['ph2_current'] / $json['ph1_current'], 3),
            'data' => [
                ['label' => 'V1', 'unit' => 'Voltage', 'phase' => '1', 'relationship_degrees' => round($json['ph1_phase_angle'], 3), 'degrees' => 0, 'angle' => round((0 * pi()) / 180, 3), 'magnitude' => round($json['ph1_ph2_volt'], 3), 'system_type' => ($json['ph1_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                ['label' => 'V2', 'unit' => 'Voltage', 'phase' => '2', 'relationship_degrees' => round($json['ph2_phase_angle'], 3), 'degrees' => 240, 'angle' => round((240 * pi()) / 180, 3), 'magnitude' => round($json['ph2_ph3_volt'], 3), 'system_type' => ($json['ph2_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                ['label' => 'V3', 'unit' => 'Voltage', 'phase' => '3', 'relationship_degrees' => round($json['ph3_phase_angle'], 3), 'degrees' => 120, 'angle' => round((120 * pi()) / 180, 3), 'magnitude' => round($json['ph3_ph1_volt'], 3), 'system_type' => ($json['ph3_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                ['label' => 'I1', 'unit' => 'Current', 'phase' => '1', 'relationship_degrees' => round($json['ph1_phase_angle'], 3), 'degrees' => round($json['ph1_phase_angle'], 3), 'angle' => round(($json['ph1_phase_angle'] * pi()) / 180, 3), 'magnitude' => round($json['ph1_current'], 3), 'system_type' => ($json['ph1_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                ['label' => 'I2', 'unit' => 'Current', 'phase' => '2', 'relationship_degrees' => round($json['ph2_phase_angle'], 3), 'degrees' => round($json['ph2_phase_angle'] + $sum_angle_2, 3), 'angle' => round((($json['ph2_phase_angle'] + $sum_angle_2) * pi()) / 180, 3), 'magnitude' => round($json['ph2_current'], 3), 'system_type' => ($json['ph2_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                ['label' => 'I3', 'unit' => 'Current', 'phase' => '3', 'relationship_degrees' => round($json['ph3_phase_angle'], 3), 'degrees' => round($json['ph3_phase_angle'] + $sum_angle_3, 3), 'angle' => round((($json['ph3_phase_angle'] + $sum_angle_3) * pi()) / 180, 3), 'magnitude' => round($json['ph3_current'], 3), 'system_type' => ($json['ph3_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO']
            ]
        ];
        $this->emit('chartPhasor', ['data' => $this->select_data]);


    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.data-chart');
    }
}
