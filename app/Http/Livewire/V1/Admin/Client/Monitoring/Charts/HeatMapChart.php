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

class HeatMapChart extends Component
{
    public $date_range_heat_map;
    public $start_heat_map;
    public $end_heat_map;
    public $start_day;
    public $end_day;
    public $series_heat_map;
    public $reactive_variables;
    public $client;
    public $data_chart_heat_map;
    public $variable_heat_map_id;
    public $heatmap_title;
    protected $listeners = ['selectHeatMap', 'dateRangeHeatMap'];

    public function mount(Client $client, $reactive_variables, $data_chart_heat_map)
    {
        $this->end_day = new Carbon();
        $this->end_heat_map = $this->end_day->format('Y-m-d');
        $this->start_day = Carbon::now()->subDay(7);
        $this->start_heat_map = $this->start_day->format('Y-m-d');
        $this->date_range_heat_map = $this->start_heat_map . " - " . $this->end_heat_map;
        $this->client = $client;
        $edit_index = [];
        $i = 0;
        foreach ($reactive_variables as $data) {
            $edit_index[$i] = $data;
            $i++;
        }
        $this->reactive_variables = $edit_index;
        $this->variable_heat_map_id = 2;
        $this->heatmap_title = "Activa (kWh)";
        $this->data_chart_heat_map = $data_chart_heat_map;
        $this->series_heat_map = [];
    }

    public function dateRangeHeatMap($start, $end)
    {
        $this->start_day = Carbon::create($start);
        $this->end_day = Carbon::create($end);
        $this->end_heat_map = $this->end_day->format('Y-m-d');
        $this->start_heat_map = $this->start_day->format('Y-m-d');
        $this->date_range_heat_map = $this->start_heat_map." - ".$this->end_heat_map;
        $this->chartRender();
    }

    public function updatedVariableHeatMapId()
    {
        if ($this->variable_heat_map_id == 2) {
            $this->heatmap_title = "Activa (kWh)";
        } elseif ($this->variable_heat_map_id == 14) {
            $this->heatmap_title ="Reactiva Inductiva (kVArLh)";
        } else {
            $this->heatmap_title ="Reactiva Capacitiva (kVArCh)";
        }
        $this->start_day = Carbon::create($this->start_heat_map);
        $this->end_day = Carbon::create($this->end_heat_map);
        $this->chartRender();
    }

    public function selectHeatMap()
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
        $this->end_day = new Carbon();
        $this->end_heat_map = $this->end_day->format('Y-m-d');
        $this->start_day = Carbon::now()->subDay(7);
        $this->start_heat_map = $this->start_day->format('Y-m-d');
        $this->date_range_heat_map = $this->start_heat_map." - ".$this->end_heat_map;
        $this->chartRender();
    }

    private function chartRender()
    {
        $max_value = 0;
        $aux = 0;
        $aux_day = Carbon::create($this->end_heat_map);
        $days = $aux_day->diffInDays($this->start_day);
        $this->series_heat_map = [];
        for ($i = 0; $i <= $days; $i++) {
            if ($i == 0) {
                $data_chart = $this->client->hourlyMicrocontrollerData()
                    ->whereDate("source_timestamp", $this->end_day->format('Y-m-d'))
                    ->orderBy('source_timestamp', 'desc')
                    ->get();
            } else {
                $data_chart = $this->client->hourlyMicrocontrollerData()
                    ->whereDate("source_timestamp", $this->end_day->subDay(1)->format('Y-m-d'))
                    ->orderBy('source_timestamp', 'desc')
                    ->get();
            }
            if (count($data_chart) > 0) {
                $array_aux = $data_chart;
                $data_aux = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                $name = "";
                foreach ($this->reactive_variables as $data) {
                    if ($data['variable_id'] == $this->variable_heat_map_id) {
                        foreach ($array_aux as $index => $item) {
                            $raw_json = json_decode($item->raw_json, true);
                            if (isset($raw_json[$data['variable_name']])) {
                                $value = round($raw_json[$data['variable_name']], 2);
                            } else {
                                $value = null;
                            }
                            $data_aux[intval($item->hour)] = $value;
                            if ($value > $max_value) {
                                $max_value = $value;
                            }
                            if ($index == 0) {
                                $name = new Carbon($item->source_timestamp);
                            }
                        }
                        $this->series_heat_map[$aux] = ["name" => $name->toFormattedDateString(), "data" => $data_aux];
                    }
                }
                $aux++;
            }
        }

        $this->emit('changeAxisHeatMap', ['series_heat_map' => $this->series_heat_map, 'max_value' => $max_value, 'title' => $this->heatmap_title]);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.heat-map-chart');
    }
}
