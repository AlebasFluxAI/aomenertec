<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\AvailableChannel;
use App\Models\V1\Client;
use App\Models\V1\ClientAlertConfiguration;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\ClientDigitalOutputAlertConfiguration;
use App\Models\V1\EquipmentType;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

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
                "mqtt_host" => config('aom.client_defaults.mqtt_host'),
                "mqtt_port" => config('aom.client_defaults.mqtt_port'),
                "mqtt_user" => config('aom.client_defaults.mqtt_user'),
                "mqtt_password" => config('aom.client_defaults.mqtt_password'),
                "real_time_latency" => config('aom.client_defaults.real_time_latency'),
                "active_real_time" => false,
                "storage_latency" => config('aom.client_defaults.storage_latency'),
                "storage_type_latency" => ClientConfiguration::STORAGE_LATENCY_TYPE_HOURLY,
                "frame_type" => ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES,
                "digital_outputs" => 0,
                "billing_day" => config('aom.client_defaults.billing_day'),

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
            if ($item->flag_id <= 42) {
                array_push($component->inputs, [
                    "input_type" => "input_min_max",
                    "input_min_model" => "client_config_alert." . $index . ".min_alert",
                    "input_max_model" => "client_config_alert." . $index . ".max_alert",
                    "input_min_number_min" => ($item->flag_id == 28 || $item->flag_id == 29 || $item->flag_id == 30 || $item->flag_id == 42)?"":0,
                    "input_min_number_max" => "",
                    "input_min_number_step" => 0.01,
                    "input_max_number_min" => "",
                    "input_max_number_max" => "",
                    "input_max_number_step" => 0.01,
                    "placeholder" => $component->placeholders[$index],
                    "col_with" => 9,
                    "required" => false,
                    "updated_input" => "lazy",
                    "placeholder_clickable" => false,
                    "data_target" => "modal_" . $item['id'],
                    "click_action" => "outputRelation('" . $item->id . "')",
                    "select_status_input" => false,

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
                    "placeholder_clickable" => false,
                    "data_target" => "modal_" . $item['id'],
                    "click_action" => "outputRelation('" . $item->id . "')",
                    "select_status_input" => false,
                ]);
            }
        }
        $component->inputs_control = [
            [
                "input_type" => "divider",
                "title" => "Rangos de control"
            ]
        ];
        foreach ($component->client_config_alert as $index => $item) {
            if ($item->flag_id <= 42) {
                array_push($component->inputs_control, [
                    "input_type" => "input_min_max",
                    "input_min_model" => "client_config_alert." . $index . ".min_control",
                    "input_max_model" => "client_config_alert." . $index . ".max_control",
                    "input_min_number_min" => ($item->flag_id == 28 || $item->flag_id == 29 || $item->flag_id == 30 || $item->flag_id == 42)?"":0,
                    "input_min_number_max" => "",
                    "input_min_number_step" => 0.01,
                    "input_max_number_min" => "",
                    "input_max_number_max" => "",
                    "input_max_number_step" => 0.01,
                    "placeholder" => $component->placeholders[$index],
                    "col_with" => 9,
                    "required" => false,
                    "updated_input" => "lazy",
                    "placeholder_clickable" => false,
                    "data_target" => "modal_" . $item['id'],
                    "click_action" => "outputRelation('" . $item->id . "')",
                    "select_status_input" => true,
                    "input_status_model" => "client_config_alert." . $index . ".status_control",
                    "select_options" => $component->control_options,
                    "select_option_title" => "key",
                    "select_option_value" => "value",
                    "select_option_view" => "key",
                ]);
            } else {
                array_push($component->inputs_control, [
                    "input_type" => "number",
                    "number_min" => 0,
                    "number_max" => "",
                    "number_step" => 0.01,
                    "offset" => 2,
                    "input_model" => "client_config_alert." . $index . ".max_control",
                    "placeholder" => $component->placeholders[$index],
                    "col_with" => 8,
                    "updated_input" => "lazy",
                    "required" => false,
                    "placeholder_clickable" => false,
                    "data_target" => "modal_" . $item['id'],
                    "click_action" => "outputRelation('" . $item->id . "')",
                    "select_status_input" => true,
                    "input_status_model" => "client_config_alert." . $index . ".status_control",
                    "select_options" => $component->control_options,
                    "select_option_title" => "key",
                    "select_option_value" => "value",
                    "select_option_view" => "key",
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
            'client_config.active_real_time' => 'required',
            'client_config.automatic_control' => 'required',
            'client_config.storage_type_latency' => 'required',
            'client_config.billing_day' => 'required',
            'client_config.digital_outputs' => 'numeric|required|min:0|max:10',
            'client_config_alert.*.min_alert' => ['required', 'numeric'],
            'client_config_alert.*.max_alert' => ['required', 'numeric'],
            'client_config_alert.*.min_control' => 'required',
            'client_config_alert.*.max_control' => 'required',
            'client_config_alert.*.status_control' => 'required',

            'checks.*.output' => 'required'
        ];
    }

    public function updated(Component $component, $propertyName, $value)
    {
        $property = explode(".", $propertyName);
        if ($property[0] == "client_config_alert") {
            $component->validate([
                'client_config_alert.' . $property[1] . '.min_alert' => ['required', 'numeric', 'max:' . $component->client_config_alert[$property[1]]->max_alert],
                'client_config_alert.' . $property[1] . '.max_alert' => ['required', 'numeric', 'min:' . $component->client_config_alert[$property[1]]->min_alert],
                'client_config_alert.' . $property[1] . '.min_control' => ['required', 'numeric', 'max:' . $component->client_config_alert[$property[1]]->max_control],
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
            'client_config_alert.' . $index . '.min_control' => ['required', 'numeric', 'max:' . $component->client_config_alert[$index]->max_control],
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
    /**
     * Send config to device via internal sub-request (no HTTP socket).
     * Uses app()->handle() to process the request in-process, bypassing the
     * single-threaded php artisan serve limitation that causes self-call deadlocks.
     */
    private function sendConfigToDevice(Component $component, $requestDetails, $event): bool
    {
        try {
            $httpMethod = strtoupper($requestDetails['method']);
            $apiKey = $requestDetails['apiKey'];
            $body = $requestDetails['body'];

            // Build the internal URL path (strip host, keep path + query)
            $urlParts = parse_url($requestDetails['url']);
            $path = $urlParts['path'] ?? '/';

            if ($httpMethod === 'GET') {
                // For GET, params go as query string
                $queryString = http_build_query($body);
                $uri = $path . '?' . $queryString;
                $internalRequest = HttpRequest::create($uri, 'GET');
            } else {
                // For POST, params go as JSON body
                $internalRequest = HttpRequest::create($path, 'POST', [], [], [], [
                    'CONTENT_TYPE' => 'application/json',
                ], json_encode($body));
                $internalRequest->headers->set('Content-Type', 'application/json');
            }

            // Set required headers
            $internalRequest->headers->set('x-api-key', $apiKey);

            // Process the request through the application kernel (same process, no network)
            $response = app()->handle($internalRequest);
            $status = $response->getStatusCode();

            // Restore the original request so Livewire continues working
            app()->instance('request', request());

            if ($status >= 200 && $status < 300) {
                Log::info("Config sent successfully: {$event} for serial {$body['serial']}");
                return true;
            }

            if ($status === 429) {
                Log::warning("Config rate-limited (429): {$event} for serial {$body['serial']}");
                $component->emitTo('livewire-toast', 'show', [
                    'type' => 'warning',
                    'message' => "Comando en proceso, espere unos segundos e intente nuevamente"
                ]);
            } else {
                Log::error("Config API error ({$status}): {$event} for serial {$body['serial']}", [
                    'response' => $response->getContent()
                ]);
                $component->emitTo('livewire-toast', 'show', [
                    'type' => 'error',
                    'message' => "Error al enviar configuración ({$status})"
                ]);
            }
            return false;
        } catch (\Exception $e) {
            Log::error("Config send exception: {$event} for serial {$requestDetails['body']['serial']}: {$e->getMessage()}");
            $component->emitTo('livewire-toast', 'show', [
                'type' => 'error',
                'message' => "Error de conexión, intente nuevamente"
            ]);
            return false;
        }
    }


    public function submitFormConection(Component $component)
    {
        $component->validate();
        $aomApiUrl = config('aom.api_internal_url');
        $aomConfigPath = config('aom.api_config_path');
        $equipment = $component->client->equipments()->whereEquipmentTypeId(7)->first();

        if (!$equipment) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "No se encontró equipo asociado al cliente"]);
            return;
        }

        $apiKey = ApiKey::first();
        if (!$apiKey) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "No se encontró API key configurada"]);
            return;
        }

        $configsSent = 0;
        $configsFailed = 0;

        if ($component->client_config->isDirty('ssid') || $component->client_config->isDirty('wifi_password')) {
            $requestDetails = [
                'url' => $aomApiUrl . $aomConfigPath . '/set-wifi-credentials',
                'method' => 'GET',
                'body' => [
                    'serial' => $equipment->serial,
                    'ssid' => $component->client_config->ssid,
                    'password' => $component->client_config->wifi_password
                ],
                'apiKey' => $apiKey->api_key
            ];
            $this->sendConfigToDevice($component, $requestDetails, EventLog::EVENT_SET_WIFI_CREDENTIALS)
                ? $configsSent++ : $configsFailed++;
        }

        if ($component->client_config->isDirty('mqtt_host') || $component->client_config->isDirty('mqtt_port') || $component->client_config->isDirty('mqtt_user') || $component->client_config->isDirty('mqtt_password')) {
            $requestDetails = [
                'url' => $aomApiUrl . $aomConfigPath . '/set-broker-credentials',
                'method' => 'GET',
                'body' => [
                    'serial' => $equipment->serial,
                    'user' => $component->client_config->mqtt_user,
                    'password' => $component->client_config->mqtt_password,
                    'host' => $component->client_config->mqtt_host,
                    'port' => $component->client_config->mqtt_port,
                ],
                'apiKey' => $apiKey->api_key
            ];
            $this->sendConfigToDevice($component, $requestDetails, EventLog::EVENT_SET_BROKER_CREDENTIALS)
                ? $configsSent++ : $configsFailed++;
        }

        if ($component->client_config->isDirty('storage_latency') || $component->client_config->isDirty('storage_type_latency') || $component->client_config->isDirty('real_time_latency')) {
            $requestDetails = [
                'url' => $aomApiUrl . $aomConfigPath . '/set-sampling-time',
                'method' => 'GET',
                'body' => [
                    'serial' => $equipment->serial,
                    'time_sampling_choice' => $component->client_config->storage_type_latency,
                    'data_per_interval' => $component->client_config->storage_latency,
                    'data_per_seconds' => $component->client_config->real_time_latency
                ],
                'apiKey' => $apiKey->api_key
            ];
            $this->sendConfigToDevice($component, $requestDetails, EventLog::EVENT_SET_SAMPLING_TIME)
                ? $configsSent++ : $configsFailed++;
        }

        if ($component->client_config->isDirty('billing_day')) {
            $requestDetails = [
                'url' => $aomApiUrl . $aomConfigPath . '/set-billing-day',
                'method' => 'GET',
                'body' => [
                    'serial' => $equipment->serial,
                    'billing_day' => $component->client_config->billing_day
                ],
                'apiKey' => $apiKey->api_key
            ];
            $this->sendConfigToDevice($component, $requestDetails, EventLog::EVENT_SET_BILLING_DAY)
                ? $configsSent++ : $configsFailed++;
        }

        if ($component->client_config->isDirty('active_real_time') || $component->client_config->isDirty('automatic_control')) {
            $component->client_config->save();
            $configsSent++;
        }

        // Show summary toast
        if ($configsSent > 0 && $configsFailed === 0) {
            $component->client_config->save();
            $component->emitTo('livewire-toast', 'show', [
                'type' => 'success',
                'message' => "Configuración enviada al equipo, esperando confirmación del dispositivo"
            ]);
        } elseif ($configsSent > 0 && $configsFailed > 0) {
            $component->client_config->save();
            $component->emitTo('livewire-toast', 'show', [
                'type' => 'warning',
                'message' => "Algunas configuraciones fueron enviadas, otras fallaron. Revise e intente nuevamente"
            ]);
        } elseif ($configsSent === 0 && $configsFailed > 0) {
            $component->emitTo('livewire-toast', 'show', [
                'type' => 'error',
                'message' => "Error al enviar la configuración, intente nuevamente"
            ]);
        } else {
            $component->emitTo('livewire-toast', 'show', [
                'type' => 'info',
                'message' => "No se detectaron cambios en la configuración"
            ]);
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
                    'client_config_alert.' . $index . '.min_alert' => ['required', 'numeric', 'max:' . $component->client_config_alert[$index]->max_alert],
                    'client_config_alert.' . $index . '.max_alert' => ['required', 'numeric', 'min:' . $component->client_config_alert[$index]->min_alert],
                ]);
            }
            $alert_config_frame = config('data-frame.alert_config_frame');
            $json = [];
            foreach ($alert_config_frame as $item) {
                if ($item['variable_name'] == 'network_operator_id') {
                    continue;
                } elseif ($item['variable_name'] == 'equipment_id') {
                    continue;
                } elseif ($item['variable_name'] == 'network_operator_new_id') {
                    continue;
                } elseif ($item['variable_name'] == 'equipment_new_id') {
                    continue;
                } else {
                    $aux_variable = $component->client_config_alert->where('flag_id', $item['flag_id'])->first();
                    $json[$item['variable_name']] = $aux_variable->{$item['limit']};
                }
            }
            foreach ($component->client_config_alert as $index => $item) {
                if ($index == "client_notification_type") {
                    continue;
                }
                $item->save();
            }
            $equipment = $component->client->equipments()->whereEquipmentTypeId(7)->first();
            if (!$equipment) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "No se encontró equipo asociado al cliente"]);
                return;
            }
            $apiKey = ApiKey::first();
            if (!$apiKey) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "No se encontró API key configurada"]);
                return;
            }
            $aomApiUrl = config('aom.api_internal_url');
            $aomConfigPath = config('aom.api_config_path');
            $requestDetails = [
                'url' => $aomApiUrl . $aomConfigPath . '/set-alert-limits',
                'method' => 'POST',
                'body' => array_merge(['serial' => $equipment->serial], $json),
                'apiKey' => $apiKey->api_key
            ];

            if ($this->sendConfigToDevice($component, $requestDetails, EventLog::EVENT_SET_ALERT_LIMITS)) {
                $component->emitTo('livewire-toast', 'show', [
                    'type' => 'success',
                    'message' => "Alertas enviadas al equipo, esperando confirmación del dispositivo"
                ]);
            }
        } catch (\Exception $e) {
            Log::error("submitFormAlert exception: {$e->getMessage()}");
            $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Error al enviar alertas"]);
        }
    }
    public function submitFormControl(Component $component)
    {
        try {
            foreach ($component->client_config_alert as $index => $item) {
                if ($index == "client_notification_type") {
                    continue;
                }
                $component->validate([
                    'client_config_alert.' . $index . '.min_control' => ['required', 'numeric', 'max:' . $component->client_config_alert[$index]->max_control],
                    'client_config_alert.' . $index . '.max_control' => ['required', 'numeric', 'min:' . $component->client_config_alert[$index]->min_control],
                ]);
            }
            $alert_config_frame = config('data-frame.alert_config_frame');
            $json = [];
            $json_status = [];
            foreach ($alert_config_frame as $item) {
                if ($item['variable_name'] == 'network_operator_id') {
                    continue;
                } elseif ($item['variable_name'] == 'equipment_id') {
                    continue;
                } elseif ($item['variable_name'] == 'network_operator_new_id') {
                    continue;
                } elseif ($item['variable_name'] == 'equipment_new_id') {
                    continue;
                } else {
                    $aux_variable = $component->client_config_alert->where('flag_id', $item['flag_id'])->first();
                    if (strpos($item['limit'], "max") !== false) {
                        $json[$item['variable_name']] = $aux_variable->max_control;
                    } else {
                        $json[$item['variable_name']] = $aux_variable->min_control;
                    }
                    $json_status[str_replace(["max_", "min_"], "status_", $item['variable_name'])] = ($aux_variable->status_control == ClientDigitalOutputAlertConfiguration::CHANGE) ? 3 : ($aux_variable->status_control == ClientDigitalOutputAlertConfiguration::ON ? 2 : 1);
                }
            }
            foreach ($component->client_config_alert as $index => $item) {
                if ($index == "client_notification_type") {
                    continue;
                }
                $item->save();
            }
            $equipment = $component->client->equipments()->whereEquipmentTypeId(7)->first();
            if (!$equipment) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "No se encontró equipo asociado al cliente"]);
                return;
            }
            $apiKey = ApiKey::first();
            if (!$apiKey) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "No se encontró API key configurada"]);
                return;
            }
            $aomApiUrl = config('aom.api_internal_url');
            $aomConfigPath = config('aom.api_config_path');

            // Send control limits (first call)
            $requestDetails = [
                'url' => $aomApiUrl . $aomConfigPath . '/set-control-limits',
                'method' => 'POST',
                'body' => array_merge(['serial' => $equipment->serial], $json),
                'apiKey' => $apiKey->api_key
            ];
            $controlLimitsSent = $this->sendConfigToDevice($component, $requestDetails, EventLog::EVENT_SET_CONTROL_LIMITS);

            // Send control status (second call)
            $requestDetailsStatus = [
                'url' => $aomApiUrl . $aomConfigPath . '/set-status-control-limits',
                'method' => 'POST',
                'body' => array_merge(['serial' => $equipment->serial], $json_status),
                'apiKey' => $apiKey->api_key
            ];
            $controlStatusSent = $this->sendConfigToDevice($component, $requestDetailsStatus, EventLog::EVENT_SET_CONTROL_LIMITS . '_status');

            if ($controlLimitsSent && $controlStatusSent) {
                $component->emitTo('livewire-toast', 'show', [
                    'type' => 'success',
                    'message' => "Configuración de control enviada al equipo, esperando confirmación"
                ]);
            } elseif ($controlLimitsSent || $controlStatusSent) {
                $component->emitTo('livewire-toast', 'show', [
                    'type' => 'warning',
                    'message' => "Configuración parcialmente enviada, intente nuevamente"
                ]);
            }
        } catch (\Exception $e) {
            Log::error("submitFormControl exception: {$e->getMessage()}");
            $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Error al enviar configuración de control"]);
        }
    }
    public function submitFormInvoicing(Component $component)
    {
        $component->client->clientConfiguration->update([
            "billing_day" => $component->invoicing_day
        ]);
        $component->client_config->save();
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);


    }

}
