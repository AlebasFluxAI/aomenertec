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


class RealTimeChart extends Component
{
    public $client;
    public $variables_rt;
    public $data_frame_rt;
    public $data_real_time;
    public $series_real_time;
    public $x_axis_real_time;
    public $variables_selected_real_time;
    public $variable_chart_id;
    public $last_data;
    public $cards_real_time;
    public $select_data;
    protected $rules = [

        'cards_real_time.*.color' => 'required',
        'cards_real_time.*.id' => 'required',
        'cards_real_time.*.icon' => 'required',
        'cards_real_time.*.list_model_variable' => 'required',
        'cards_real_time.*.variables_selected' => 'required',
    ];

    public function mount(Client $client, $variables, $data_frame)
    {
        $this->select_data = false;
        $this->client = $client;
        $this->variables_rt = $variables;
        $this->data_frame_rt = $data_frame;
        $this->data_real_time = [];
        $this->series_real_time = [];
        $this->x_axis_real_time = [];
        $this->variable_chart_id = 17;
        $this->variables_selected_real_time = $this->data_frame_rt->where('variable_id', $this->variable_chart_id)->all();
        $aux = $variables->where('id', $this->variable_chart_id)->first();
        $this->chart_title = $aux['display_name'];
        $this->chart_type = $aux['chart_type'];
        $this->last_data = [];
        $this->cards_real_time = [];
        $initial_variables = $variables->take(3);
        foreach ($initial_variables as $variable) {
            $aux = [];
            $var_data_frame = $this->data_frame_rt->where('variable_id', $variable['id'])->all();
            foreach ($var_data_frame as $item) {
                $item['value'] = 0;
                array_push($aux, $item);
            }
            array_push($this->cards_real_time, [
                "id" => $variable['id'],
                "color" => $variable['style'],
                "icon" => $variable['icon'],
                "list_model_variable" => $variable['id'],
                "variables_selected" => $aux,
            ]);
        }
    }

    public function updatedCardsRealTime($value, $key)
    {
        $variable_select = $this->variables_rt->where('id', $value)->first();
        $id = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
        $aux = [];
        $var_data_frame = $this->data_frame_rt->where('variable_id', $value)->all();
        foreach ($var_data_frame as $item) {
            if (count($this->last_data) > 0) {
                $item['value'] = round($this->last_data[$item['variable_name']], 2);
            } else {
                $item['value'] = 0;
            }
            array_push($aux, $item);
        }
        $this->cards_real_time[$id]['id'] = $variable_select['id'];
        $this->cards_real_time[$id]['color'] = $variable_select['style'];
        $this->cards_real_time[$id]['icon'] = $variable_select['icon'];
        $this->cards_real_time[$id]['variables_selected'] = $aux;
    }


    public function getListeners()
    {
        return [
            "echo:data-monitoring." . $this->client->id . ",.dataEventRealTime" => 'addPoint',
            "selectRealTime"
        ];
    }

    public function updatedVariableChartId()
    {
        $variable = $this->variables_rt->where('id', $this->variable_chart_id)->first();
        $this->chart_type = $variable['chart_type'];
        $this->chart_title = $variable['display_name'];
        $this->variables_selected_real_time = $this->data_frame_rt->where('variable_id', $this->variable_chart_id);
        $data_aux = [];
        $this->series_real_time = [];
        $this->x_axis_real_time = [];
        $index = 0;
        foreach ($this->variables_selected_real_time as $variable) {
            $data_aux[$index] = [];
            foreach ($this->data_real_time as $item) {
                $x = Carbon::create($item['timestamp'])->format('d F H:i:s');
                if ($variable['start'] <= 430) {
                    array_push($data_aux[$index], ["x" => $x, "y" => round($item[$variable['variable_name']], 2)]);
                }
            }
            $this->series_real_time[$index] = ["name" => $variable['display_name'], "data" => $data_aux[$index]];
            $index++;
        }
        $this->emit('addPointRealTime', ['series' => $this->series_real_time, 'title' => $this->chart_title]);
    }

    public function selectRealTime()
    {
        if ($this->client->clientConfiguration()->first()->active_real_time) {
                $equipment = $this->client->equipments()->whereEquipmentTypeId(7)->first();
                if (!RealTimeListener::whereUserId(Auth::user()->id)
                    ->whereEquipmentId($equipment->id)->exists()) {
                    $new = RealTimeListener::create([
                        "user_id" => Auth::user()->id,
                        "equipment_id" => $equipment->id
                    ]);
                    if (!RealTimeListener::whereEquipmentId($equipment->id)->where('id', '!=', $new->id)->exists()) {
                        $equipment= $this->client->equipments()->whereEquipmentTypeId(7)->first();
                        $apiKey =ApiKey::first();
                        $requestDetails = [
                            'url' => 'https://aom.enerteclatam.com/api/v1/config/set-status-real-time',
                            'method' => 'GET',
                            'body' => [
                                'serial' => $equipment->serial,
                                'status' => 1
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
        } else {
            $this->emit('addPointRealTime', ['series' => [], 'title' => "", 'no_data' => 'El dispositivo no cuenta con conexión wifi...']);
        }
    }

    public function addPoint($data)
    {
        if (count($this->data_real_time) == 40) {
            array_shift($this->data_real_time);
        }
        array_push($this->data_real_time, $data['data']);
        $data_aux = [];
        $this->series_real_time = [];
        $this->x_axis_real_time = [];
        $index = 0;
        foreach ($this->variables_selected_real_time as $variable) {
            $data_aux[$index] = [];
            foreach ($this->data_real_time as $item) {
                $x = Carbon::create($item['timestamp'])->format('d F H:i:s');
                array_push($data_aux[$index], ["x" => $x, "y" => round($item[$variable['variable_name']], 2)]);
            }
            $this->series_real_time[$index] = ["name" => $variable['display_name'], "data" => $data_aux[$index]];
            $index++;
        }
        $this->last_data = $data['data'];
        foreach ($this->cards_real_time as $index => $card) {
            $aux = [];
            $var_data_frame = $this->data_frame_rt->where('variable_id', $card['id'])->all();
            foreach ($var_data_frame as $item) {
                $item['value'] = round($this->last_data[$item['variable_name']], 2);
                array_push($aux, $item);
            }
            $this->cards_real_time[$index]["variables_selected"] = $aux;
        }
        if ($data['data']['total_phase_angle'] < 0) {
            $sum_angle_2 = -120;
            $sum_angle_3 = -240;
        } else {
            $sum_angle_2 = 240;
            $sum_angle_3 = 120;
        }
        $this->select_data = ['tittle' => 'phasor', 'lineFrecuency' => 60, 'samplesPerCycle' => 32, 'percent_volt' => ($data['data']['ph1_ph2_volt'] == 0) ? 0 : round($data['data']['ph2_ph3_volt'] / $data['data']['ph1_ph2_volt'], 3), 'percent_curr' => ($data['data']['ph1_current'] == 0) ? 0 : round($data['data']['ph2_current'] / $data['data']['ph1_current'], 3),
            'data' => [
                ['label' => 'V1', 'unit' => 'Voltage', 'phase' => '1', 'relationship_degrees' => round($data['data']['ph1_phase_angle'], 3), 'degrees' => 0, 'angle' => round((0 * pi()) / 180, 3), 'magnitude' => round($data['data']['ph1_ph2_volt'], 3), 'system_type' => ($data['data']['ph1_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                ['label' => 'V2', 'unit' => 'Voltage', 'phase' => '2', 'relationship_degrees' => round($data['data']['ph2_phase_angle'], 3), 'degrees' => 240, 'angle' => round((240 * pi()) / 180, 3), 'magnitude' => round($data['data']['ph2_ph3_volt'], 3), 'system_type' => ($data['data']['ph2_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                ['label' => 'V3', 'unit' => 'Voltage', 'phase' => '3', 'relationship_degrees' => round($data['data']['ph3_phase_angle'], 3), 'degrees' => 120, 'angle' => round((120 * pi()) / 180, 3), 'magnitude' => round($data['data']['ph3_ph1_volt'], 3), 'system_type' => ($data['data']['ph3_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                ['label' => 'I1', 'unit' => 'Current', 'phase' => '1', 'relationship_degrees' => round($data['data']['ph1_phase_angle'], 3), 'degrees' => round($data['data']['ph1_phase_angle'], 3), 'angle' => round(($data['data']['ph1_phase_angle'] * pi()) / 180, 3), 'magnitude' => round($data['data']['ph1_current'], 3), 'system_type' => ($data['data']['ph1_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                ['label' => 'I2', 'unit' => 'Current', 'phase' => '2', 'relationship_degrees' => round($data['data']['ph2_phase_angle'], 3), 'degrees' => round($data['data']['ph2_phase_angle'] + $sum_angle_2, 3), 'angle' => round((($data['data']['ph2_phase_angle'] + $sum_angle_2) * pi()) / 180, 3), 'magnitude' => round($data['data']['ph2_current'], 3), 'system_type' => ($data['data']['ph2_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                ['label' => 'I3', 'unit' => 'Current', 'phase' => '3', 'relationship_degrees' => round($data['data']['ph3_phase_angle'], 3), 'degrees' => round($data['data']['ph3_phase_angle'] + $sum_angle_3, 3), 'angle' => round((($data['data']['ph3_phase_angle'] + $sum_angle_3) * pi()) / 180, 3), 'magnitude' => round($data['data']['ph3_current'], 3), 'system_type' => ($data['data']['ph3_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO']
            ]
        ];
        $this->emit('addPointRealTime', ['data' => $this->select_data, 'series' => $this->series_real_time, 'title' => $this->chart_title, 'no_data' => 'No hay datos']);
        //$this->emit('addPointRealTime', [ 'series' => $this->series_real_time, 'title' => $this->chart_title, 'no_data'=> 'No hay datos']);
        $this->emit('animatedRealTime');
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.real-time-chart');
    }
}
