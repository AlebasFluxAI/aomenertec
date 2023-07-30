<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Channels\WhatsAppChannel;
use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Http\Services\Singleton;
use App\Models\V1\AdminConfiguration;
use App\Models\V1\AdminPrice;
use App\Models\V1\AvailableChannel;
use App\Models\V1\ClientAlertConfiguration;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\ClientDigitalOutputAlertConfiguration;
use App\Models\V1\EquipmentClient;
use App\Models\V1\ClientType;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Client;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use App\Notifications\Alert\AlertControlNotification;
use Illuminate\Support\Str;
use Livewire\Component;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class ClientConfigurationService extends Singleton
{
    public function mount(Component $component, Client $client)
    {
        $component->control_options = ClientDigitalOutputAlertConfiguration::CONTROL_OPTIONS;
        $component->channels = $client->channels;
        $component->model = $client;
        $flags_frame = collect(config('data-frame.flags_frame'));
        $alerts = $flags_frame->where('id', '>=', 16)->all();
        $component->placeholders = [];
        foreach ($alerts as $item) {
            array_push($component->placeholders, $item['placeholder']);
        }
        if (!$client->clientAlertConfiguration()->exists()) {
            foreach ($alerts as $item) {
                ClientAlertConfiguration::create([
                    "client_id" => $client->id,
                    "flag_id" => $item['id'],
                    "min_alert" => 0,
                    "max_alert" => 0,
                    "min_control" => 0,
                    "max_control" => 0,
                    "active_control" => false,
                ]);
            }
        }
        if (!$client->clientConfiguration()->exists()) {
            ClientConfiguration::create([
                "client_id" => $client->id,
                "ssid" => "",
                "wifi_password" => "",
                "mqtt_host" => "3.12.98.178",
                "mqtt_port" => "1883",
                "mqtt_user" => "enertec",
                "mqtt_password" => "enertec2020**",
                "real_time_latency" => 30,
                "active_real_time" => false,
                "storage_latency" => 1,
                "storage_type_latency" => ClientConfiguration::STORAGE_LATENCY_TYPE_HOURLY,
                "frame_type" => ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES,
                "digital_outputs" => 0,

            ]);
        }

        $component->fill([
            "invoicing_day" => $component->client->clientConfiguration->billing_day,
            "client" => $client,
            "client_config" => $client->clientConfiguration,
            'active_real_time' => $client->clientConfiguration->active_real_time,
            "client_config_alert" => $client->clientAlertConfiguration,
            "digital_outputs" => $client->digitalOutputs,
            "frame_types" => [
                ["key" => "Consumo activo", "value" => ClientConfiguration::FRAME_TYPE_ACTIVE_ENERGY],
                ["key" => "Consumo activo + reactivo", "value" => ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY],
                ["key" => "Consumo activo + reactivo + variables de red", "value" => ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES]
            ],
            'storage_latency_types' => [
                ["key" => "# Lecturas por hora", "value" => ClientConfiguration::STORAGE_LATENCY_TYPE_HOURLY],
                ["key" => "# Lecturas por dia", "value" => ClientConfiguration::STORAGE_LATENCY_TYPE_DAILY],
                ["key" => "Día de lectura", "value" => ClientConfiguration::STORAGE_LATENCY_TYPE_MONTHLY]
            ],
            "notification_types" => [
                ["key" => "WhatsApp", "value" => AvailableChannel::CHANNEL_WHATSAPP],
                ["key" => "Email", "value" => AvailableChannel::CHANNEL_EMAIL]
            ],
            'storage_latency_options' => ClientConfiguration::STORAGE_LATENCY_OPTIONS[$client->clientConfiguration->storage_type_latency],
        ]);
        foreach ($component->digital_outputs as $index => $output) {
            $component->checks[$index] = ["id" => $output->id, "output" => false, "control_status" => ClientDigitalOutputAlertConfiguration::CHANGE];
        }
        $alert_config_frame = collect(config('data-frame.alert_config_frame'));
        $component->inputs = [
            [
                "input_type" => "divider",
                "title" => "Rangos alarmables"
            ]
        ];
        foreach ($component->client_config_alert as $index => $item) {
            if ($item->flag_id < 47) {
                array_push($component->inputs, [
                    "input_type" => "input_min_max",
                    "input_min_model" => "client_config_alert." . $index . ".min_alert",
                    "input_max_model" => "client_config_alert." . $index . ".max_alert",
                    "input_min_number_min" => 0,
                    "input_min_number_max" => "",
                    "input_min_number_step" => 0.01,
                    "input_max_number_min" => "",
                    "input_max_number_max" => "",
                    "input_max_number_step" => 0.01,
                    "placeholder" => $component->placeholders[$index],
                    "col_with" => 9,
                    "required" => false,
                    "updated_input" => "lazy",
                    "placeholder_clickable" => ($component->client_config->digital_outputs > 0) ? true : false,
                    "data_target" => "modal_" . $item['id'],
                    "click_action" => "outputRelation('" . $item->id . "')"
                ]);
            } else {
                array_push($component->inputs, [
                    "input_type" => "number",
                    "number_min" => 0,
                    "number_max" => "",
                    "number_step" => 0.01,
                    "offset" => 2,
                    "input_model" => "client_config_alert." . $index . ".max_alert",
                    "placeholder" => $component->placeholders[$index],
                    "col_with" => 8,
                    "updated_input" => "lazy",
                    "required" => false,
                    "placeholder_clickable" => ($component->client_config->digital_outputs > 0) ? true : false,
                    "data_target" => "modal_" . $item['id'],
                    "click_action" => "outputRelation('" . $item->id . "')"
                ]);
            }
        }
    }


    public function rules()
    {
        return [
            'client_config.ssid' => 'required',
            'client_config.wifi_password' => 'required',
            'client_config.mqtt_host' => 'required',
            'client_config.mqtt_port' => 'required',
            'client_config.mqtt_user' => 'required',
            'client_config.mqtt_password' => 'required',
            'client_config.real_time_latency' => 'numeric|required|min:10',
            'client_config.storage_latency' => 'required',
            'client_config.storage_type_latency' => 'required',
            'client_config.billing_day' => 'required',
            'client_config.digital_outputs' => 'numeric|required|min:0|max:10',
            'client_config_alert.*.min_alert' => ['required', 'numeric'],
            'client_config_alert.*.max_alert' => ['required', 'numeric'],
            'client_config_alert.*.min_control' => 'required',
            'client_config_alert.*.max_control' => 'required',

            'checks.*.output' => 'required'
        ];
    }

    public function updated(Component $component, $propertyName, $value)
    {
        $property = explode(".", $propertyName);
        if ($property[0] == "client_config_alert") {
            $component->validate([
                'client_config_alert.' . $property[1] . '.min_alert' => ['required', 'numeric', 'min:0', 'max:' . $component->client_config_alert[$property[1]]->max_alert],
                'client_config_alert.' . $property[1] . '.max_alert' => ['required', 'numeric', 'min:' . $component->client_config_alert[$property[1]]->min_alert],
                'client_config_alert.' . $property[1] . '.min_control' => ['required', 'numeric', 'min:0', 'max:' . $component->client_config_alert[$property[1]]->max_control],
                'client_config_alert.' . $property[1] . '.max_control' => ['required', 'numeric', 'min:' . $component->client_config_alert[$property[1]]->min_control],
            ]);
        } else {
            $component->validateOnly($propertyName);
        }
    }

    public function updatedClientConfig(Component $component, $value, $key)
    {
        if ($key == "digital_outputs") {
            $component->validateOnly("client_config." . $key);
            if ($value >= 0) {
                $component->digital_outputs = $component->client->digitalOutputs()->get();
                if ($component->digital_outputs) {
                    $i = count($component->digital_outputs);
                } else {
                    $i = 0;
                }
                if ($i < $value) {
                    for ($i = $i + 1; $i <= $value; $i++) {
                        ClientDigitalOutput::create([
                            'client_id' => $component->client->id,
                            'number' => $i,
                            'name' => 'Salida ' . $i,
                            'status' => true,
                        ]);
                    }
                } else {
                    for ($i; $i > $value; $i--) {
                        $delete = $component->client->digitalOutputs()->where('number', $i)->first();
                        $delete->delete();
                    }
                }
            }
            $component->client_config->digital_outputs = $value;
            $component->client_config->save();
            $component->digital_outputs = $component->client->digitalOutputs()->get();
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Salidas configuradas"]);
            return redirect()->route("v1.admin.client.settings", ['client' => $component->client->id]);
        } elseif ($key == "storage_type_latency") {
            $component->storage_latency_options = ClientConfiguration::STORAGE_LATENCY_OPTIONS[$value];
        }
    }

    public function outputRelation(Component $component, $id)
    {
        $component->digital_outputs = $component->client->digitalOutputs()->get();
        $alert_ouputs = ClientAlertConfiguration::find($id)->outputs()->get();
        if (ClientAlertConfiguration::find($id)->outputs()->exists()) {
            foreach ($alert_ouputs as $index => $output) {
                foreach ($component->checks as $i => $check) {
                    if ($check['id'] == $output->id) {
                        $component->checks[$i]['output'] = true;
                        $component->checks[$i]['control_status'] = $output->pivot->control_status;
                        break;
                    }
                }
            }
        } else {
            foreach ($component->checks as $i => $check) {
                $component->checks[$i]['output'] = false;
                $component->checks[$i]['control_status'] = ClientDigitalOutputAlertConfiguration::CHANGE;
            }
        }
    }

    public function assignmentOutput(Component $component, $id, $index)
    {
        $alert = ClientAlertConfiguration::find($id);
        $flag = false;
        $component->validate([
            'client_config_alert.' . $index . '.min_control' => ['required', 'numeric', 'min:0', 'max:' . $component->client_config_alert[$index]->max_control],
            'client_config_alert.' . $index . '.max_control' => ['required', 'numeric', 'min:' . $component->client_config_alert[$index]->min_control],
        ]);
        foreach ($component->checks as $check) {
            $relation = ClientDigitalOutputAlertConfiguration::where('client_alert_configuration_id', $id)->where('client_digital_output_id', $check['id'])->first();
            if ($check['output']) {
                ClientDigitalOutputAlertConfiguration::updateOrCreate([
                    'client_alert_configuration_id' => $id,
                    'client_digital_output_id' => $check['id']], [
                    'control_status' => $check['control_status']
                ]);
                $flag = true;
            } else {
                if (ClientDigitalOutputAlertConfiguration::where('client_alert_configuration_id', $id)->where('client_digital_output_id', $check['id'])->exists()) {
                    $relation->delete();
                }
            }
        }
        if ($flag) {
            $alert->active_control = true;
        } else {
            $alert->active_control = false;
        }
        $alert->save();
        foreach ($component->checks as $i => $check) {
            $component->checks[$i]['output'] = false;
            $component->checks[$i]['control_status'] = ClientDigitalOutputAlertConfiguration::CHANGE;
        }
        $component->emit('closeModal', ["id" => $id]);

        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Asignacion realizada, Recuerde guardar cambios"]);
    }

    public function submitFormConection(Component $component)
    {
        $component->validate();
        $flag = false;
        $message = [];
        if ($component->client_config->isDirty('ssid')) {
            $message['ssid'] = strval($component->client_config->ssid);
            $flag = true;
        }
        if ($component->client_config->isDirty('wifi_password')) {
            $message['pass'] = $component->client_config->wifi_password;
            $flag = true;
        }
        if ($component->client_config->isDirty('mqtt_host')) {
            $message['brokerMqtt'] = $component->client_config->mqtt_host;
            $flag = true;
        }
        if ($component->client_config->isDirty('mqtt_port')) {
            $message['portMqtt'] = $component->client_config->mqtt_port;
            $flag = true;
        }
        if ($component->client_config->isDirty('mqtt_user')) {
            $message['userMqtt'] = $component->client_config->mqtt_user;
            $flag = true;
        }
        if ($component->client_config->isDirty('mqtt_password')) {
            $message['passMqtt'] = $component->client_config->mqtt_password;
            $flag = true;
        }
        if ($component->client_config->isDirty('storage_latency')
            || $component->client_config->isDirty('storage_type_latency')) {
            $message['storage_latency'] = $component->client_config->storage_latency;
            $message['storage_latency_type'] = substr($component->client_config->storage_type_latency, 0, 1);
            $flag = true;
        }
        if ($component->client_config->isDirty('real_time_latency')) {
            $message['real_time_latency'] = $component->client_config->real_time_latency;
            $flag = true;
        }
        try {
            if ($flag) {
                $equipment = $component->client->equipments()->whereEquipmentTypeId(1)->first();
                $message['did'] = $equipment->serial;
                $topic = "mc/config/" . $equipment->serial;
                $mqtt = MQTT::connection('default', 'client_aux');
                $mqtt->publish($topic, json_encode($message));
                $mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) use ($component) {
                    if ($elapsedTime >= 50) {
                        $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Fallo la conexión"]);
                        $mqtt->interrupt();
                    }
                });
                $mqtt->subscribe('mc/ack', function (string $topic, string $message) use ($component, $mqtt) {
                    $json = json_decode($message, true);
                    if (array_key_exists('config_ack', $json)) {
                        $equipment_serial = str_pad($json['did'], 6, "0", STR_PAD_LEFT);
                        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                            ->first();
                        if ($equipment) {
                            $client_aux = $equipment->clients()->first();
                            if ($client_aux->id == $component->client->id) {
                                if ($json['config_ack']) {
                                    if ($component->client_config->save()) {
                                        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);
                                    }
                                    foreach ($component->client->refresh()->channels as $channel) {
                                        $channel->disable();
                                    }
                                    /*foreach ($component->client_notification_types as $notification_channel) {
                                        AvailableChannel::find($component->client->refresh()->channels()->whereChannel($notification_channel)->first())->enable();
                                    }*/
                                    $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);
                                }
                            }
                        }
                    }
                    $mqtt->interrupt();
                }, 1);
                $mqtt->loop(true);
                $mqtt->disconnect();
            }
        } catch (MqttClientException $e) {

        }
    }

    public function submitFormPermission(Component $component)
    {
        $config = ClientConfiguration::find($component->client_config->id);
        $config->active_real_time = $component->active_real_time;
        if ($config->save()) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);
        }
    }


    public function blinkChannel(Component $component, $channel)
    {
        AvailableChannel::find($channel)->blink();
        ToastEvent::launchToast($component, "show", "success", "Canal configurado exitosamente");
        $component->channels = $component->client->refresh()->channels;
    }

    public function submitFormAlert(Component $component)
    {
        try {
            foreach ($component->client_config_alert as $index => $item) {
                if ($index == "client_notification_type") {
                    continue;
                }
                $component->validate([
                    'client_config_alert.' . $index . '.min_alert' => ['required', 'numeric', 'min:0', 'max:' . $component->client_config_alert[$index]->max_alert],
                    'client_config_alert.' . $index . '.max_alert' => ['required', 'numeric', 'min:' . $component->client_config_alert[$index]->min_alert],
                    'client_config_alert.' . $index . '.min_control' => ['required', 'numeric', 'min:0', 'max:' . $component->client_config_alert[$index]->max_control],
                    'client_config_alert.' . $index . '.max_control' => ['required', 'numeric', 'min:' . $component->client_config_alert[$index]->min_control],
                ]);
            }
            
            $mqtt = MQTT::connection('default', 'client_aux');
            $mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) use ($component) {
                if ($elapsedTime >= 50) {
                    $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Fallo la conexión"]);
                    $mqtt->interrupt();
                }
            });
            $alert_config_frame = config('data-frame.alert_config_frame');
            $equipment = $component->client->equipments()->whereEquipmentTypeId(1)->first();
            $topic = "mc/config/" . $equipment->serial;
            $binary_data = [];
            $data = "";
            foreach ($alert_config_frame as $item) {
                if ($item['variable_name'] == 'network_operator_id') {
                    $data = $component->client->networkOperator->identification;
                } elseif ($item['variable_name'] == 'equipment_id') {
                    $data = $equipment->serial;
                } elseif ($item['variable_name'] == 'network_operator_new_id') {
                    $data = $component->client->networkOperator->identification;
                } elseif ($item['variable_name'] == 'equipment_new_id') {
                    $data = $equipment->serial;
                } else {
                    $aux_variable = $component->client_config_alert->where('flag_id', $item['flag_id'])->first();
                    $data = $aux_variable->{$item['limit']};
                }
                array_push($binary_data, pack($item['type'], $data));
            }
            $message = base64_encode(implode($binary_data));
           dd($message);
           $mqtt->publish($topic, $message);
            
            $mqtt->subscribe('mc/ack', function (string $topic, string $message) use ($component, $mqtt) {
                $json = json_decode($message, true);
                if (array_key_exists('config_ack', $json)) {
                    $equipment_serial = str_pad($json['did'], 6, "0", STR_PAD_LEFT);
                    $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                        ->first();
                    if ($equipment) {
                        $client_aux = $equipment->clients()->first();
                        if ($client_aux->id == $component->client->id) {
                            if ($json['config_ack']) {


                                foreach ($component->client_config_alert as $index => $item) {
                                    if ($index == "client_notification_type") {
                                        continue;
                                    }
                                    $item->save();
                                }
                                $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);
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

    public function submitFormInvoicing(Component $component)
    {
        $component->client->clientConfiguration->update([
            "billing_day" => $component->invoicing_day
        ]);
    }

}
