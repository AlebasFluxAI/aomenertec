<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;

use App\Models\V1\Client;
use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\RealTimeListener;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpMqtt\Client\Facades\MQTT;

class Control extends Component
{

    public $client;
    public $coils;

    protected $rules = [
        'coils.*.id' => 'required',
        'coils.*.number' => 'required',
        'coils.*.name' => 'required',
        'coils.*.status' => 'required',
        'coils.*.control_type' => 'required',
    ];
    protected $listeners = ['selectControl'];

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->coils = $this->client->digitalOutputs;
    }

    public function confirmAction($index)
    {
        $this->emitTo('livewire-toast', 'show', ['type' => 'info', 'message' => "conectando con dispositivo...", 'duration'=>5000]);
        $this->coils[$index]['status'] = !$this->coils[$index]['status'];
        $equipment = $this->client->equipments()->whereEquipmentTypeId(1)->first();
        $topic = "mc/config/" . $equipment->serial;
        if ($this->coils[$index]['status']) {
            $message = "{\"coil" . $this->coils[$index]['number'] . "\":true}";
        } else {
            $message = "{\"coil" . $this->coils[$index]['number'] . "\":false}";
        }
        $mqtt=MQTT::connection();
        $mqtt->publish($topic, $message);

        $mqtt->subscribe('some/topic', function (string $topic, string $message) use ($index, $mqtt) {
            echo sprintf('Received QoS level 1 message on topic [%s]: %s', $topic, $message);
            $this->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Accion realizada"]);
            $coil = ClientDigitalOutput::find($this->coils[$index]['id']);
            $coil->status = $this->coils[$index]['status'];
            $coil->save();
            $mqtt->interrupt();
        }, 1);
        $mqtt->loop(true);
        MQTT::disconnect();




        //$this->coils = $this->client->coils;
    }

    public function updatedCoils($value, $key)
    {
        $variable = explode(".", $key);
        if ($variable[1] == "name") {
            $coil = ClientDigitalOutput::find($this->coils[$variable[0]]['id']);
            $coil->name = $value;
            $coil->save();
            $this->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Nombre actualizado"]);
        }
    }

    public function selectControl()
    {
        if ($this->client->clientConfiguration()->first()->active_real_time) {
            if ($this->client->clientConfiguration()->first()->real_time_flag) {
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
                        MQTT::publish($topic, $message);
                        MQTT::disconnect();
                    }
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.control')
            ->extends('layouts.v1.app');
    }
}
