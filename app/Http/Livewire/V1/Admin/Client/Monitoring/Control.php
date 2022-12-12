<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;

use App\Models\V1\Client;
use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\EquipmentType;
use App\Models\V1\RealTimeListener;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;
use Psr\Log\LogLevel;
use PhpMqtt\Client\Exceptions\MqttClientException;use function PHPUnit\Framework\once;

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
        $equipment = $this->client->equipments()->whereEquipmentTypeId(1)->first();
        $topic = "mc/config/" . $equipment->serial;
        if ($this->coils[$index]['status']) {
            $message = "{\"coil" . $this->coils[$index]['number'] . "\":false}";
        } else {
            $message = "{\"coil" . $this->coils[$index]['number'] . "\":true}";
        }
        try {

            $mqtt=MQTT::connection();
            $mqtt->publish($topic, $message);
            $mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) use ($index) {
                if ($elapsedTime >= 50) {
                    $this->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Fallo la conexion"]);
                    $mqtt->interrupt();
                    $this->emit('changeCheck', ['index'=>$index, 'flag'=>false]);
                }
            });
            $mqtt->subscribe('mc/ack', function (string $topic, string $message) use ($index, $mqtt, &$result) {
                $json = json_decode($message, true);
                if (array_key_exists('coil_ack', $json)) {
                    $equipment_serial = str_pad($json['did'], 6, "0", STR_PAD_LEFT);
                    $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                        ->first();
                    if ($equipment) {
                        $client = $equipment->clients()->first();
                        if ($client->id == $this->client->id) {
                            if ($json['coil_ack']) {
                                $this->coils[$index]['status'] = !$this->coils[$index]['status'];
                                $this->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Accion realizada"]);
                                $coil = ClientDigitalOutput::find($this->coils[$index]['id']);
                                $this->emit('changeCheck', ['index'=>$coil->id, 'flag'=>true]);
                                $coil->status = $this->coils[$index]['status'];
                                $coil->save();
                                //$this->coils = $this->client->coils;
                                $mqtt->interrupt();
                            }
                        }
                    }
                }
            }, 1);
            $mqtt->loop(true);
            $mqtt->disconnect();
        } catch (MqttClientException $e) {

        }
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
