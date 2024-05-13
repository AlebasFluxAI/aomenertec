<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;

use App\Exports\V1\MultipleSheetsMonitoringData;
use App\Http\Resources\V1\ToastEvent;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use App\Models\V1\WorkOrder;
use App\ModulesAux\MQTT;
use App\Strategy\MqttSenderPattern\FetchDataApiStrategy;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use PhpMqtt\Client\Exceptions\MqttClientException;

class DataReport extends Component
{
    public $client;
    public $variables;
    public $data_frame;
    public $checks;
    public $start_report;
    public $end_report;
    public $date_range_report;

    public $aux_start;
    public $aux_end;
    public $start_simulator;
    public $end_simulator;
    public $date_range_simulator;
    public $kwh_cost;
    public $total_consumption;
    public $monitoring_fee;


    public $variables_selected;
    public $time_report_id;
    public $end_day;
    public $total_simulation;
    protected $listeners = ['dateRangeReport', 'selectReport', "dateRangeSimulator", 'selectSimulator'];

    public function mount(Client $client, $variables, $data_frame)
    {
        $this->time_report_id = 2;
        $this->variables_selected = [];
        $this->client = $client;
        $this->variables = $variables;
        $this->data_frame = $data_frame;

        $start = Carbon::now();
        $end = Carbon::now();

        $this->start_report = $start->format('Y-m-d 00:00:00');
        $this->end_report = $end->format('Y-m-d 23:59:59');
        $this->end_day = Carbon::create($this->end_report);
        $this->date_range_report = $start->format('Y-m-d') . " - " . $end->format('Y-m-d');
        $this->monitoring_fee = $client->monitoring_fee;

        $this->start_simulator = $start->format('Y-m-d 00:00:00');
        $this->end_simulator = $end->format('Y-m-d 23:59:59');
        $this->end_day = Carbon::create($this->end_simulator);
        $this->date_range_simulator = $start->format('Y-m-d') . " - " . $end->format('Y-m-d');


        $index = 0;
        $this->variables->push(
            ['id' => 33, 'display_name' => 'Matriz de reactivos']
        );
    }

    public function submitMonitoringFeeForm()
    {
        $this->client->update([
            "monitoring_fee" => $this->monitoring_fee
        ]);
        ToastEvent::launchToast($this, "show", "success", "Tarifa actualizada exitosamente");

    }

    public function dateRangeReport($start, $end)
    {
        $this->aux_start = Carbon::create($start);
        $this->aux_end = Carbon::create($end);
        $this->date_range_report = $this->aux_start->format('Y-m-d') . " - " . $this->aux_end->format('Y-m-d');
        $this->start_report = $start;
        $this->end_report = $end;
    }

    public function simulateFee()
    {
        $this->dateRangeSimulator($this->aux_start, $this->aux_end);
    }

    public function dateRangeSimulator($start, $end)
    {
        try {

            $this->aux_start = Carbon::create($start);
            $this->aux_end = Carbon::create($end);
            $this->date_range_simululator = $this->aux_start->format('Y-m-d') . " - " . $this->aux_end->format('Y-m-d');
            $this->start_simululator = $start;
            $this->end_simululator = $end;
            if (!$this->kwh_cost) {
                return;
            }
            $microcontrollerData = $this->client->microcontrollerData()
                ->whereBetween("source_timestamp", [$this->start_simululator, $this->end_simululator])
                ->whereIsAlert(false)
                ->orderBy('source_timestamp')
                ->get();
            $first_data = $microcontrollerData->first()->accumulated_real_consumption;
            $last_data = $microcontrollerData->last()->accumulated_real_consumption;
            $this->total_consumption = ($last_data - $first_data);
            $this->total_simulation = $this->total_consumption * $this->kwh_cost;

        } catch (\Throwable) {
            ToastEvent::launchToast($this, "show", "error", "Ocurrio un error al calcular tarifa");
        }

    }

    public function reportCsv()
    {
        if ($this->start_report != "") {
            if (count($this->variables_selected) > 0) {
                $array = $this->arrayCreate();
                return Excel::download(new MultipleSheetsMonitoringData($array), 'data_' . $this->client->identification . '_' . Carbon::now()->format('Y-m-d H:i:s') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            }
        }
    }

    private function arrayCreate()
    {
        if ($this->time_report_id == 1) {
            $data_report = $this->client->microcontrollerData()
                ->whereBetween("source_timestamp", [$this->start_report, $this->end_report])
                ->whereIsAlert(false)
                ->orderBy('source_timestamp')
                ->limit(15000)->get();
            $array_title = ["ANIO", "MES", "DIA", "HORA", "MINUTO"];
        } elseif ($this->time_report_id == 2) {
            $data_report = $this->client->hourlyMicrocontrollerData()
                ->whereBetween("source_timestamp", [$this->start_report, $this->end_report])
                ->orderBy('source_timestamp')
                ->limit(15000)->get();
            $array_title = ["ANIO", "MES", "DIA", "HORA"];
        } elseif ($this->time_report_id == 3) {
            $data_report = $this->client->dailyMicrocontrollerData()
                ->whereHas('microcontrollerData', function ($query) {
                    $query->whereBetween("source_timestamp", [$this->start_report, $this->end_report]);
                })
                ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                ->limit(15000)->get();
            $array_title = ["ANIO", "MES", "DIA"];
        } else {
            $data_report = $this->client->monthlyMicrocontrollerData()
                ->whereHas('microcontrollerData', function ($query) {
                    $query->whereBetween("source_timestamp", [$this->start_report, $this->end_report]);
                })
                ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                ->limit(15000)->get();
            $array_title = ["ANIO", "MES"];
        }
        if (count($data_report) > 0) {
            foreach ($this->variables_selected as $variable) {
                if ($variable != 33) {
                    $variables_name = $this->data_frame->where('variable_id', $variable);
                    foreach ($variables_name as $name) {
                        array_push($array_title, $name['display_name']);
                    }
                }
            }
            foreach ($data_report as $index => $data) {
                if ($this->time_report_id == 1) {
                    $date = Carbon::create($data->source_timestamp);
                    $array[$index] = [intval($date->format('Y')), intval($date->format('m')), intval($date->format('d')), intval($date->format('H')), intval($date->format('i'))];
                    $raw_json = json_decode($data->raw_json, true);
                } elseif ($this->time_report_id == 2) {
                    $array[$index] = [intval($data->year), intval($data->month), intval($data->day), intval($data->hour)];
                    $raw_json = json_decode($data->raw_json, true);
                } elseif ($this->time_report_id == 3) {
                    $array[$index] = [intval($data->year), intval($data->month), intval($data->day)];
                    $raw_json = json_decode($data->raw_json, true);
                } else {
                    $array[$index] = [intval($data->year), intval($data->month)];
                    $raw_json = json_decode($data->raw_json, true);
                }
                foreach ($this->variables_selected as $variable) {
                    if ($variable != 33) {
                        $variables_name = $this->data_frame->where('variable_id', $variable);
                        foreach ($variables_name as $name) {
                            array_push($array[$index], round($raw_json[$name['variable_name']], 2));
                        }
                    }
                }
            }
            array_unshift($array, $array_title);
            $array_report = [$array];
            if (in_array(33, $this->variables_selected)) {
                $array_penalizable = $this->arrayCreateReactive();
                foreach ($array_penalizable as $item) {
                    array_push($array_report, $item);
                }
            }
            return $array_report;
        }
    }

    private function arrayCreateReactive()
    {
        $title = ["DIA/HORA", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"];
        $this->end_day = Carbon::create($this->end_report);
        $start_day = Carbon::create($this->start_report);
        $aux_day = Carbon::create($this->end_day);
        $active = [];
        $inductive = [];
        $capacitive = [];
        $inductive_pen = [];
        $capacitive_pen = [];
        $days = $aux_day->diffInDays($start_day);
        for ($i = 0; $i <= $days; $i++) {
            if ($i == 0) {
                $data_report = $this->client->hourlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereDate('source_timestamp', $this->end_day->format('Y-m-d'));
                    })
                    ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                    ->get();
            } else {
                $data_report = $this->client->hourlyMicrocontrollerData()
                    ->whereHas('microcontrollerData', function ($query) {
                        $query->whereDate('source_timestamp', $this->end_day->subDay(1)->format('Y-m-d'));
                    })
                    ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                    ->get();
            }
            if (count($data_report) > 0) {
                $aux_active = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                $aux_inductive = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                $aux_capacitive = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                $aux_inductive_pen = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                $aux_capacitive_pen = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                foreach ($data_report as $index => $data) {
                    $aux_active[intval($data->hour)] = $data->interval_real_consumption;
                    $aux_inductive[intval($data->hour)] = $data->interval_reactive_inductive_consumption;
                    $aux_capacitive[intval($data->hour)] = $data->interval_reactive_capacitive_consumption;
                    $aux_inductive_pen[intval($data->hour)] = $data->penalizable_reactive_inductive_consumption;
                    $aux_capacitive_pen[intval($data->hour)] = $data->penalizable_reactive_capacitive_consumption;
                    if ($index == 0) {
                        $day = Carbon::create($data->microcontrollerData->source_timestamp)->format('Y-m-d');
                    }
                }
                array_unshift($aux_active, $day);
                array_unshift($aux_inductive, $day);
                array_unshift($aux_capacitive, $day);
                array_unshift($aux_inductive_pen, $day);
                array_unshift($aux_capacitive_pen, $day);
                array_push($active, $aux_active);
                array_push($inductive, $aux_inductive);
                array_push($capacitive, $aux_capacitive);
                array_push($inductive_pen, $aux_inductive_pen);
                array_push($capacitive_pen, $aux_capacitive_pen);
            }
        }
        array_unshift($active, $title);
        array_unshift($inductive, $title);
        array_unshift($capacitive, $title);
        array_unshift($inductive_pen, $title);
        array_unshift($capacitive_pen, $title);
        $array_penalizable = [$active, $inductive, $capacitive, $inductive_pen, $capacitive_pen];
        return $array_penalizable;
    }

    public function reportPdf()
    {
        //if ($this->start_report != "") {
        //$array = $this->arrayCreate();
        $work_order = WorkOrder::find(27);
        $network_operator = $work_order->client->networkOperator;
        $pdf = PDF::loadView('reports.orden_work_report', [
            'work_order' => $work_order,
            'client' => $work_order->client,
            'network_operator' => $network_operator,
            'admin' => $network_operator->admin
        ]);
        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'export.pdf');
        // }
    }

    public function selectReport()
    {
        if ($this->client->clientConfiguration()->first()->active_real_time) {
            if ($this->client->clientConfiguration()->first()->real_time_flag) {
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
        }
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.data-report');
    }
}
