<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpMqtt\Client\Facades\MQTT;

class Monitoring extends Component
{
    protected $listeners = ['tabChange'];
    public $data_chart;
    public $data_frame;
    public $variables;
    public $client;
    public $reactive_variables;
    public $real_time_variables;

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->data_frame = collect(config('data-frame.data_frame'));
        $this->variables = collect(config('data-frame.variables'));
        $this->reactive_variables = $this->data_frame->whereIn('variable_id', [2, 14, 10])->toArray();
        $this->real_time_variables = $this->variables->where('real_time', true);
        $this->data_chart = $this->client->dailyMicrocontrollerData()->limit(24)->get();
    }

    public function tabChange()
    {
        $equipment =$this->client->equipmentsClient()->whereEquipmentTypeId(1)->first();
        RealTimeListener::whereUserId(Auth::user()->id)
            ->whereEquipmentId(
                $equipment->id
            )->delete();

        if (!RealTimeListener::whereEquipmentId(
            $equipment->id)->exists()) {
            $message = "{'did':" . $equipment->serial . ",'realTimeFlag':false}";
            MQTT::publish('mc/config', $message);
            MQTT::disconnect();
        }
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring')
            ->extends('layouts.v1.app');
    }
}
