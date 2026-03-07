<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


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
    public $chart_title;
    public $chart_type;
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
        if ($aux) {
            $this->chart_title = $aux['display_name'];
            $this->chart_type = $aux['chart_type'];
        } else {
            $this->chart_title = '';
            $this->chart_type = 'line';
        }
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
        if (!$variable_select) {
            return;
        }
        $id = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
        $aux = [];
        $var_data_frame = $this->data_frame_rt->where('variable_id', $value)->all();
        foreach ($var_data_frame as $item) {
            if (count($this->last_data) > 0 && isset($this->last_data[$item['variable_name']])) {
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
        $clientId = $this->client ? $this->client->id : 0;
        return [
            "echo:data-monitoring." . $clientId . ",.dataEventRealTime" => 'addPoint',
            "selectRealTime"
        ];
    }

    public function updatedVariableChartId()
    {
        $variable = $this->variables_rt->where('id', $this->variable_chart_id)->first();
        if (!$variable) {
            return;
        }
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
                if (!isset($item['timestamp']) || !isset($item[$variable['variable_name']])) {
                    continue;
                }
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
        if (!$this->client) {
            $this->emit('addPointRealTime', ['series' => [], 'title' => "", 'no_data' => 'Error: cliente no encontrado.']);
            return;
        }

        $clientConfig = $this->client->clientConfiguration()->first();

        if (!$clientConfig) {
            $this->emit('addPointRealTime', ['series' => [], 'title' => "", 'no_data' => 'El cliente no tiene configuración. Contacte al administrador.']);
            return;
        }

        if ($clientConfig->active_real_time) {
                $equipment = $this->client->equipments()->whereEquipmentTypeId(7)->first();

                if (!$equipment) {
                    $this->emit('addPointRealTime', ['series' => [], 'title' => "", 'no_data' => 'No se encontró el microcontrolador asociado al cliente.']);
                    return;
                }

                // Limpiar listeners zombie (más de 30 minutos sin actividad)
                // Esto evita que sesiones abandonadas bloqueen la activación del streaming.
                RealTimeListener::whereEquipmentId($equipment->id)
                    ->where('updated_at', '<', now()->subMinutes(30))
                    ->delete();

                $alreadyListening = RealTimeListener::whereUserId(Auth::user()->id)
                    ->whereEquipmentId($equipment->id)->exists();

                if (!$alreadyListening) {
                    $new = RealTimeListener::create([
                        "user_id" => Auth::user()->id,
                        "equipment_id" => $equipment->id
                    ]);

                    // Solo hay listeners activos si después de crear el nuestro existen otros
                    // (que no son zombie — ya se limpiaron arriba).
                    $othersExist = RealTimeListener::whereEquipmentId($equipment->id)
                        ->where('id', '!=', $new->id)
                        ->exists();

                    if (!$othersExist) {
                        // Somos el primer listener activo: activar streaming en el dispositivo
                        $apiKey = ApiKey::first();

                        if (!$apiKey) {
                            $this->emit('addPointRealTime', ['series' => [], 'title' => "", 'no_data' => 'Error de configuración: no se encontró API key.']);
                            return;
                        }

                        // Dispatch background job to avoid Http::localhost deadlock
                        // on single-threaded php artisan serve.
                        \App\Jobs\V1\Api\ConfigurationClient\SendRealTimeStatusJob::dispatch(
                            $equipment->serial,
                            1,
                            $apiKey->api_key
                        )->onQueue('spot2');

                        $this->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Activación enviada. Los datos llegarán en unos segundos."]);
                    }
                }
        } else {
            $this->emit('addPointRealTime', ['series' => [], 'title' => "", 'no_data' => 'El dispositivo no cuenta con conexión wifi...']);
        }
    }

    public function addPoint($data)
    {
        // Validar que el broadcast data tenga la estructura esperada
        if (!isset($data['data']) || !is_array($data['data'])) {
            return;
        }

        $pointData = $data['data'];

        if (count($this->data_real_time) == 40) {
            array_shift($this->data_real_time);
        }
        array_push($this->data_real_time, $pointData);
        $data_aux = [];
        $this->series_real_time = [];
        $this->x_axis_real_time = [];
        $index = 0;
        foreach ($this->variables_selected_real_time as $variable) {
            $data_aux[$index] = [];
            foreach ($this->data_real_time as $item) {
                if (!isset($item['timestamp']) || !isset($item[$variable['variable_name']])) {
                    continue;
                }
                $x = Carbon::create($item['timestamp'])->format('d F H:i:s');
                array_push($data_aux[$index], ["x" => $x, "y" => round($item[$variable['variable_name']], 2)]);
            }
            $this->series_real_time[$index] = ["name" => $variable['display_name'], "data" => $data_aux[$index]];
            $index++;
        }
        $this->last_data = $pointData;
        foreach ($this->cards_real_time as $index => $card) {
            $aux = [];
            $var_data_frame = $this->data_frame_rt->where('variable_id', $card['id'])->all();
            foreach ($var_data_frame as $item) {
                $item['value'] = isset($this->last_data[$item['variable_name']])
                    ? round($this->last_data[$item['variable_name']], 2)
                    : 0;
                array_push($aux, $item);
            }
            $this->cards_real_time[$index]["variables_selected"] = $aux;
        }

        // Construir datos de phasor solo si los campos requeridos están presentes
        $requiredKeys = ['total_phase_angle', 'ph1_ph2_volt', 'ph2_ph3_volt', 'ph3_ph1_volt',
            'ph1_current', 'ph2_current', 'ph3_current',
            'ph1_phase_angle', 'ph2_phase_angle', 'ph3_phase_angle'];

        $hasAllKeys = true;
        foreach ($requiredKeys as $key) {
            if (!isset($pointData[$key])) {
                $hasAllKeys = false;
                break;
            }
        }

        if ($hasAllKeys) {
            if ($pointData['total_phase_angle'] < 0) {
                $sum_angle_2 = -120;
                $sum_angle_3 = -240;
            } else {
                $sum_angle_2 = 240;
                $sum_angle_3 = 120;
            }
            $this->select_data = ['tittle' => 'phasor', 'lineFrecuency' => 60, 'samplesPerCycle' => 32, 'percent_volt' => ($pointData['ph1_ph2_volt'] == 0) ? 0 : round($pointData['ph2_ph3_volt'] / $pointData['ph1_ph2_volt'], 3), 'percent_curr' => ($pointData['ph1_current'] == 0) ? 0 : round($pointData['ph2_current'] / $pointData['ph1_current'], 3),
                'data' => [
                    ['label' => 'V1', 'unit' => 'Voltage', 'phase' => '1', 'relationship_degrees' => round($pointData['ph1_phase_angle'], 3), 'degrees' => 0, 'angle' => round((0 * pi()) / 180, 3), 'magnitude' => round($pointData['ph1_ph2_volt'], 3), 'system_type' => ($pointData['ph1_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                    ['label' => 'V2', 'unit' => 'Voltage', 'phase' => '2', 'relationship_degrees' => round($pointData['ph2_phase_angle'], 3), 'degrees' => 240, 'angle' => round((240 * pi()) / 180, 3), 'magnitude' => round($pointData['ph2_ph3_volt'], 3), 'system_type' => ($pointData['ph2_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                    ['label' => 'V3', 'unit' => 'Voltage', 'phase' => '3', 'relationship_degrees' => round($pointData['ph3_phase_angle'], 3), 'degrees' => 120, 'angle' => round((120 * pi()) / 180, 3), 'magnitude' => round($pointData['ph3_ph1_volt'], 3), 'system_type' => ($pointData['ph3_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                    ['label' => 'I1', 'unit' => 'Current', 'phase' => '1', 'relationship_degrees' => round($pointData['ph1_phase_angle'], 3), 'degrees' => round($pointData['ph1_phase_angle'], 3), 'angle' => round(($pointData['ph1_phase_angle'] * pi()) / 180, 3), 'magnitude' => round($pointData['ph1_current'], 3), 'system_type' => ($pointData['ph1_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                    ['label' => 'I2', 'unit' => 'Current', 'phase' => '2', 'relationship_degrees' => round($pointData['ph2_phase_angle'], 3), 'degrees' => round($pointData['ph2_phase_angle'] + $sum_angle_2, 3), 'angle' => round((($pointData['ph2_phase_angle'] + $sum_angle_2) * pi()) / 180, 3), 'magnitude' => round($pointData['ph2_current'], 3), 'system_type' => ($pointData['ph2_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO'],
                    ['label' => 'I3', 'unit' => 'Current', 'phase' => '3', 'relationship_degrees' => round($pointData['ph3_phase_angle'], 3), 'degrees' => round($pointData['ph3_phase_angle'] + $sum_angle_3, 3), 'angle' => round((($pointData['ph3_phase_angle'] + $sum_angle_3) * pi()) / 180, 3), 'magnitude' => round($pointData['ph3_current'], 3), 'system_type' => ($pointData['ph3_phase_angle'] > 0) ? 'INDUCTIVO' : 'CAPACITIVO']
                ]
            ];
        }

        $this->emit('addPointRealTime', ['data' => $this->select_data, 'series' => $this->series_real_time, 'title' => $this->chart_title, 'no_data' => 'No hay datos']);
        $this->emit('animatedRealTime');
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.real-time-chart');
    }
}
