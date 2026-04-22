<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Models\V1\Api\ApiKey;
use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DataChart extends Component
{
    public $client_id;
    public $variables;
    public $variables_selected;
    public $data_frame;
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

    // No serializar data_chart entre requests - solo calcular cuando se necesita
    protected $data_chart;

    // Propiedad computada para evitar serialización del modelo completo
    public function getClientProperty()
    {
        return Client::find($this->client_id);
    }

    /**
     * Extrae source_timestamp de forma segura desde un modelo de datos agregados.
     * Los modelos hourly/daily/monthly tienen una relación belongsTo ->microcontrollerData
     * que puede retornar null si el FK está roto o el registro fue eliminado.
     */
    private function getSourceTimestamp($dataModel)
    {
        if (!$dataModel) {
            return null;
        }

        // time_id 1 y 2 tienen source_timestamp directo
        if ($this->time_id == 1 || $this->time_id == 2) {
            return $dataModel->source_timestamp;
        }

        // time_id 3 y 4 (daily/monthly) necesitan la relación microcontrollerData
        $mcData = $dataModel->microcontrollerData;
        return $mcData ? $mcData->source_timestamp : null;
    }

    public function mount(Client $client, $variables, $data_frame, $data_chart, $time)
    {
        $this->select_data = false;
        $this->client_id = $client->id;
        $this->variables = $variables;
        $this->data_frame = $data_frame;
        $this->variable_chart_id = 1;
        $this->variables_selected = $this->data_frame->where('variable_id', $this->variable_chart_id)->all();
        $aux = $variables->where('id', $this->variable_chart_id)->first();
        if ($aux) {
            $this->chart_title = $aux['display_name'];
            $this->chart_type = $aux['chart_type'];
        } else {
            $this->chart_title = '';
            $this->chart_type = 'line';
        }
        $this->time_id = $time;
        $this->series = [];
        $this->x_axis = [];
        $this->data_chart = $client->hourlyMicrocontrollerData()->orderBy('source_timestamp', 'desc')->limit(24)->get();
        if (count($this->data_chart) > 0) {
            $firstTs = $this->getSourceTimestamp($this->data_chart->first());
            $lastTs = $this->getSourceTimestamp($this->data_chart->last());

            if ($firstTs && $lastTs) {
                if ($time == 1 or $time == 2) {
                    $this->end = $firstTs;
                    $this->start = $lastTs;
                } else {
                    $this->start = $firstTs;
                    $this->end = $lastTs;
                }
                $this->date_range = $this->start . " - " . $this->end;
            }
        } else {
            $this->data_chart = [];
        }

        $this->chartRender(true);
    }

    public function restartDateRange()
    {
        $client = $this->client;
        if (!$client) {
            return;
        }

        if ($this->time_id == 1) {
            $this->data_chart = $client->microcontrollerData()->orderBy('source_timestamp', 'desc')->limit(60)->get();
        } elseif ($this->time_id == 2) {
            $this->data_chart = $client->hourlyMicrocontrollerData()->orderBy('source_timestamp', 'desc')->limit(24)->get();
        } elseif ($this->time_id == 3) {
            $this->data_chart = $client->dailyMicrocontrollerData()->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')->limit(31)->get();
        } else {
            $this->data_chart = $client->monthlyMicrocontrollerData()->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')->limit(12)->get();
        }
        if (count($this->data_chart) > 0) {
            $firstTs = $this->getSourceTimestamp($this->data_chart->first());
            $lastTs = $this->getSourceTimestamp($this->data_chart->last());

            if ($firstTs && $lastTs) {
                if ($this->time_id == 1 || $this->time_id == 2) {
                    $this->end = $firstTs;
                    $this->start = $lastTs;
                } else {
                    $this->end = $firstTs;
                    $this->start = $lastTs;
                }
                $this->date_range = $this->start . " - " . $this->end;
            }
        }
        $this->chartRender(true);
    }

    public function selectHistory()
    {
        $client = $this->client;
        if (!$client) {
            return;
        }

        $user = Auth::user();
        if (!$user) {
            return;
        }

        $clientConfig = $client->clientConfiguration()->first();

        if ($clientConfig && $clientConfig->active_real_time) {
            $equipment = $client->equipments()->whereEquipmentTypeId(7)->first();

            if ($equipment && RealTimeListener::whereUserId($user->id)
                ->whereEquipmentId($equipment->id)->exists()) {
                RealTimeListener::whereUserId($user->id)
                    ->whereEquipmentId($equipment->id)->forceDelete();

                if (!RealTimeListener::whereEquipmentId($equipment->id)->exists()) {
                    $apiKey = ApiKey::first();

                    if ($apiKey) {
                        // Dispatch background job to avoid Http::localhost deadlock
                        // on single-threaded php artisan serve.
                        \App\Jobs\V1\Api\ConfigurationClient\SendRealTimeStatusJob::dispatch(
                            $equipment->serial,
                            0,
                            $apiKey->api_key
                        )->onQueue('spot2');
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
        if (!$variable) {
            return;
        }
        $this->chart_type = $variable['chart_type'];
        $this->chart_title = $variable['display_name'];
        $this->variables_selected = $this->data_frame->where('variable_id', $this->variable_chart_id);
        $this->chartRender(false);
    }



    private function chartRender($flag)
    {
        $client = $this->client;

        if ($flag) {
            $data_chart = $this->data_chart;
        } else {
            if (!$client) {
                $this->emit('changeAxis', ['series' => [], 'x_axis' => [], 'title' => $this->chart_title]);
                return;
            }

            if ($this->time_id == 1) {
                $data_chart = $client->microcontrollerData()
                    ->whereBetween("source_timestamp", [$this->start, $this->end])
                    ->orderBy('source_timestamp', 'desc')
                    ->limit(250)->get();
            } elseif ($this->time_id == 2) {
                $data_chart = $client->hourlyMicrocontrollerData()
                    ->whereBetween("source_timestamp", [$this->start, $this->end])
                    ->orderBy('source_timestamp', 'desc')
                    ->limit(250)->get();
            } elseif ($this->time_id == 3) {
                $data_chart = $client->dailyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start, $this->end]);
                    })
                    ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                    ->limit(250)->get();
            } else {
                $data_chart = $client->monthlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereBetween("source_timestamp", [$this->start, $this->end]);
                    })
                    ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                    ->limit(250)->get();
            }
            $this->data_chart = $data_chart;
        }

        if ($data_chart && count($data_chart) > 0) {
            $firstTs = $this->getSourceTimestamp($this->data_chart->first());
            $lastTs = $this->getSourceTimestamp($this->data_chart->last());

            if ($firstTs && $lastTs) {
                if ($this->time_id == 1 or $this->time_id == 2) {
                    $this->end = $firstTs;
                    $this->start = $lastTs;
                } else {
                    $this->end = $firstTs;
                    $this->start = $lastTs;
                }
                $this->date_range = $this->start . " - " . $this->end;
            }

            $array_aux = $data_chart->reverse();
            $this->series = [];
            $data_aux = [];
            $this->x_axis = [];
            $index = 0;
            foreach ($this->variables_selected as $data) {
                $data_aux[$index] = [];
                foreach ($array_aux as $item) {
                    $raw_json = json_decode($item->raw_json, true);
                    if ($raw_json && isset($raw_json[$data['variable_name']])) {
                        array_push($data_aux[$index], round($raw_json[$data['variable_name']], 4));
                    } else {
                        array_push($data_aux[$index], null);
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

    /**
     * Re-carga data_chart desde la DB ya que es protected y no se serializa entre requests Livewire.
     */
    private function loadDataChart()
    {
        if ($this->data_chart) {
            return $this->data_chart;
        }

        $client = $this->client;
        if (!$client) {
            return collect();
        }

        if (!$this->start || !$this->end) {
            return collect();
        }

        if ($this->time_id == 1) {
            return $client->microcontrollerData()
                ->whereBetween("source_timestamp", [$this->start, $this->end])
                ->orderBy('source_timestamp', 'desc')
                ->limit(250)->get();
        } elseif ($this->time_id == 2) {
            return $client->hourlyMicrocontrollerData()
                ->whereBetween("source_timestamp", [$this->start, $this->end])
                ->orderBy('source_timestamp', 'desc')
                ->limit(250)->get();
        } elseif ($this->time_id == 3) {
            return $client->dailyMicrocontrollerData()
                ->whereHas('microcontrollerData', function ($query) {
                    $query->whereBetween("source_timestamp", [$this->start, $this->end]);
                })
                ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                ->limit(250)->get();
        } else {
            return $client->monthlyMicrocontrollerData()
                ->whereHas('microcontrollerData', function ($query) {
                    $query->whereBetween("source_timestamp", [$this->start, $this->end]);
                })
                ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                ->limit(250)->get();
        }
    }

    public function setPointPhasor($point)
    {
        $data_chart = $this->loadDataChart();

        if (!$data_chart || count($data_chart) === 0) {
            return;
        }

        $data = $data_chart->reverse();
        $json = null;
        $i = 0;
        foreach ($data as $datum) {
            if ($i == $point) {
                $json = json_decode($datum->raw_json, true);
                break;
            }
            $i++;
        }

        if (!$json || !isset($json['total_phase_angle'])) {
            return;
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
