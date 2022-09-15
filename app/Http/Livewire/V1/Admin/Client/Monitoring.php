<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;

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


    public function mount(Client $client)
    {
        $this->client = $client;
        $this->clientAlerts = $this->client->clientAlerts;
        foreach ($this->clientAlerts as &$alert){
            $alert->name = $alert->clientAlertConfiguration->getVariableName();
        }
        $this->data_frame = collect(config('data-frame.data_frame'));
        $this->variables = collect(config('data-frame.variables'));
        $this->reactive_variables = $this->data_frame->whereIn('variable_id', [2, 14, 10])->toArray();
        $this->real_time_variables = $this->variables->where('real_time', true);
        $this->time = 2;
        $this->data_chart = $this->client->hourlyMicrocontrollerData()->limit(24)->get();
        if (count($this->data_chart)==0) {
            $this->data_chart = $this->client->microcontrollerData()->orderBy('source_timestamp', 'desc')->limit(60)->get();
            $this->time = 1;
        }
    }

    public function tabChange()
    {
        $equipment =$this->client->equipments()->whereEquipmentTypeId(1)->first();
        RealTimeListener::whereUserId(Auth::user()->id)
            ->whereEquipmentId(
                $equipment->id
            )->delete();

        if (!RealTimeListener::whereEquipmentId(
            $equipment->id
        )->exists()) {
            $message = "{'did':" . $equipment->serial . ",'realTimeFlag':false}";
            $topic = 'mc/config/'.$equipment->serial;
            MQTT::publish($topic, $message);
            MQTT::disconnect();
        }
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring')
            ->extends('layouts.v1.app');
    }
}
