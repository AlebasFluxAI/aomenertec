<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;

use App\Models\V1\Client;
use App\Models\V1\ClientDigitalOutput;
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
    public function mount(Client $client)
    {
        $this->client = $client;
        $this->coils = $this->client->digitalOutputs;
    }

    public function confirmAction($index)
    {
        $this->coils[$index]['status']=!$this->coils[$index]['status'];
        $equipment =$this->client->equipments()->whereEquipmentTypeId(1)->first();
        $topic = "mc/config/".$equipment->serial;
        if ($this->coils[$index]['status']) {
            $message = "{'did':" . $equipment->serial . ",'coil" . $this->coils[$index]['number'] . "':false}";
        } else {
            $message = "{'did':" . $equipment->serial . ",'coil" . $this->coils[$index]['number'] . "':true}";
        }
        $coil = ClientDigitalOutput::find($this->coils[$index]['id']);
        $coil->status = $this->coils[$index]['status'];
        $coil->save();

        MQTT::publish($topic, $message);
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

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.control');
    }
}
