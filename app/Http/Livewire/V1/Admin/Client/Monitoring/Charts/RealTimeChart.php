<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;
use PhpMqtt\Client\Facades\MQTT;

class RealTimeChart extends Component
{
    public $client;
    public $variables;
    public $data_frame;
    public $data_real_time;
    public $series_real_time;
    public $x_axis_real_time;
    public $variables_selected_real_time;
    public $variable_chart_id;
    public $variables_selected;
    public $last_data;
    public $cards_real_time;
    public $real_time_flag;
    protected $rules = [

        'cards_real_time.*.color' => 'required',
        'cards_real_time.*.id' => 'required',
        'cards_real_time.*.icon' => 'required',
        'cards_real_time.*.list_model_variable' => 'required',
        'cards_real_time.*.variables_selected' => 'required',
    ];

    public function mount(Client $client, $variables, $data_frame)
    {
        $this->real_time_flag = true;
        $this->client = $client;
        $this->variables = $variables;
        $this->data_frame = $data_frame;
        $this->data_real_time = [];
        $this->series_real_time = [];
        $this->x_axis_real_time = [];
        $this->variable_chart_id = 17;
        $this->variables_selected_real_time = $this->data_frame->where('variable_id', $this->variable_chart_id)->all();
        $aux = $variables->where('id', $this->variable_chart_id)->first();
        $this->chart_title = $aux['display_name'];
        $this->chart_type = $aux['chart_type'];
        $last_data = $this->client->microcontrollerData()->latest()->first();
        $this->last_data = collect(json_decode($last_data->raw_json, true));
        $this->cards_real_time = [];
        $this->variables_selected = [];
        $initial_variables = $variables->take(6);
        foreach ($initial_variables as $variable) {
            $aux = [];
            $var_data_frame = $this->data_frame->where('variable_id', $variable['id'])->all();
            foreach ($var_data_frame as $item) {
                $item['value'] = round($this->last_data[$item['variable_name']], 2);
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
        $variable_select = $this->variables->where('id', $value)->first();
        $id = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
        $aux = [];
        $var_data_frame = $this->data_frame->where('variable_id', $value)->all();
        foreach ($var_data_frame as $item) {
            $item['value'] = round($this->last_data[$item['variable_name']], 2);
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
        $variable = $this->variables->where('id', $this->variable_chart_id)->first();
        $this->chart_type = $variable['chart_type'];
        $this->chart_title = $variable['display_name'];
        $this->variables_selected_real_time = $this->data_frame->where('variable_id', $this->variable_chart_id);
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
        $this->emit('addPointRealTime', ['series' => $this->series_real_time, 'title' => $this->chart_title]);
    }

    public function selectRealTime()
    {
        $equipment = $this->client->equipmentsClient()->whereEquipmentTypeId(1)->first();
        RealTimeListener::whereUserId(Auth::user()->id)
            ->whereEquipmentId(
                $equipment->id
            )->delete();
        RealTimeListener::create([
            "user_id" => Auth::user()->id,
            "equipment_id" => $equipment->id
        ]);

        $message = "{'did':" . $equipment->serial . ",'realTimeFlag':true}";
        MQTT::publish('mc/config', $message);
        MQTT::disconnect();
        //MQTT:disconnect();
    }

    public function addPoint($data)
    {
        if (count($this->data_real_time) == 20) {
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
            $var_data_frame = $this->data_frame->where('variable_id', $card['id'])->all();
            foreach ($var_data_frame as $item) {
                $item['value'] = round($this->last_data[$item['variable_name']], 2);
                array_push($aux, $item);
            }
            $this->cards_real_time[$index]["variables_selected"] = $aux;
        }
        $this->emit('addPointRealTime', ['series' => $this->series_real_time, 'title' => $this->chart_title]);
        $this->emit('animatedRealTime');
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.real-time-chart');
    }
}
