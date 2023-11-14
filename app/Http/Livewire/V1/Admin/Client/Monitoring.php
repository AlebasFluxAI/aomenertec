<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\RealTimeListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\ModulesAux\MQTT;

class Monitoring extends Component
{
    use WithPagination;

    protected $listeners = ['tabChange'];
    public $data_chart;
    public $data_frame;
    public $variables;
    public $client;
    public $reactive_variables;
    public $real_time_variables;
    public $time;
    public $clientAlerts;
    public $data_chart_result;
    public $model;


    public function mount(Client $client)
    {
        $this->model = $client;
        $this->clientAlerts = $this->client->clientAlerts;
        foreach ($this->clientAlerts as &$alert) {
            $alert->name = $alert->clientAlertConfiguration->getVariableName();
        }
        $this->data_frame = collect(config('data-frame.data_frame'));
        $this->variables = collect(config('data-frame.variables'));
        $this->reactive_variables = $this->data_frame->whereIn('variable_id', [2, 14, 10])->toArray();
        $this->real_time_variables = $this->variables->where('real_time', true);
        $this->time = 2;
        $first_day = Carbon::now();
        $this->data_chart_result = $this->client->hourlyMicrocontrollerData()
            ->where('year', $first_day->format('Y'))
            ->where('month', $first_day->format('m'))
            ->where('day', 01)
            ->get();
        $this->data_chart = $this->client->hourlyMicrocontrollerData()->orderBy('source_timestamp', 'desc')->limit(24)->get();
        if (count($this->data_chart) == 0) {
            $this->data_chart = $this->client->microcontrollerData()->orderBy('source_timestamp', 'desc')->limit(60)->get();
            $this->time = 1;
        }
    }

    public function tabChange()
    {
        if ($this->client->clientConfiguration()->first()->active_real_time) {
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
                    $mqtt = MQTT::connection('default', 'null');
                    $mqtt->publish($topic, $message);
                    $mqtt->disconnect();
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring')
            ->extends('layouts.v1.app');
    }
}
