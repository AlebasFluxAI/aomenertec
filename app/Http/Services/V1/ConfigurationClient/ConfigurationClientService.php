<?php

namespace App\Http\Services\V1\ConfigurationClient;

use App\Http\Repositories\ConfigurationClient\ConfigClientRepository;
use App\Http\Resources\Json\V1\ConfigurationDefaultResponseResource;
use App\Http\Resources\Json\V1\ErrorResource;
use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class ConfigurationClientService
{
    protected $configurationClientRepository;

    public function __construct(ConfigClientRepository $configurationClientRepository)
    {
        $this->configurationClientRepository = $configurationClientRepository;
    }

    protected function serialValidationLogic($attribute, $value, $fail, $request)
    {
        $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
        if ($equipment_type != null) {
            $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
                ->where('serial', $value)->first();
            if ($equipment == null) {
                $equipment_type = EquipmentType::where('type', 'GABINETE')->first();
                $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
                    ->where('serial', $value)->first();
                if($equipment == null) {
                    $fail("El medidor electrico con serial " . $value . " no existe");
                }
            } else {
                $client = $equipment->clients()->first();
                if ($client == null) {
                    $fail("El medidor electrico con serial " . $value . " no a sido asignado a ningun cliente");
                }
            }
        }
    }

    public function setAlertLimitsForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        $alert_config_frame = config('data-frame.alert_config_frame');
        $reglas = [];
        foreach ($alert_config_frame as $index => $item) {
            if ($item['variable_name'] != 'network_operator_id' and $item['variable_name'] != 'equipment_id' and $item['variable_name'] != 'network_operator_new_id' and $item['variable_name'] != 'equipment_new_id') {
                if (strpos($item['variable_name'], 'min') !== false) {
                    $reglas[$item['variable_name']] = 'required|numeric|lte:'.$alert_config_frame[$index-1]['variable_name'];
                } else{
                    $reglas[$item['variable_name']] = 'required|numeric';
                }
            }
        }
        $validator = Validator::make($request->json()->all(), $reglas);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setControlLimitsForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        $alert_config_frame = config('data-frame.alert_config_frame');
        $reglas = [];
        foreach ($alert_config_frame as $index => $item) {
            if ($item['variable_name'] != 'network_operator_id' and $item['variable_name'] != 'equipment_id' and $item['variable_name'] != 'network_operator_new_id' and $item['variable_name'] != 'equipment_new_id') {
                if (strpos($item['variable_name'], 'min') !== false) {
                    $reglas[$item['variable_name']] = 'required|numeric|lte:'.$alert_config_frame[$index-1]['variable_name'];
                } else{
                    $reglas[$item['variable_name']] = 'required|numeric';
                }
            }
        }
        $validator = Validator::make($request->json()->all(), $reglas);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setControlStatusForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        $alert_config_frame = config('data-frame.alert_config_frame');
        $reglas = [];
        $flag_id = 0;
        foreach ($alert_config_frame as $index => $item) {
            if ($item['variable_name'] != 'network_operator_id' and $item['variable_name'] != 'equipment_id' and $item['variable_name'] != 'network_operator_new_id' and $item['variable_name'] != 'equipment_new_id') {
                if ($flag_id != $item['flag_id']) {
                    $reglas[str_replace(["max_", "min_"], "status_", $item['variable_name'])] = 'required|numeric';
                    $flag_id = $item['flag_id'];
                }
            }
        }
        $validator = Validator::make($request->json()->all(), $reglas);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setAlertTimeForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        $alert_config_frame = config('data-frame.alert_config_time_frame');
        $reglas = [];
        foreach ($alert_config_frame as $item) {
            if ($item['variable_name'] != 'network_operator_id' and $item['variable_name'] != 'equipment_id' and $item['variable_name'] != 'network_operator_new_id' and $item['variable_name'] != 'equipment_new_id') {
                $reglas[$item['variable_name']] = 'required';
            }
        }
        $validator = Validator::make($request->json()->all(), $reglas);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setSamplingTimeForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
            'time_sampling_choice' => [
                'required',
                'string',
                'in:hourly,daily,monthly',
            ],
            'data_per_interval' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    $timeSamplingChoice = $request->input('time_sampling_choice');

                    if ($timeSamplingChoice === 'hourly') {
                        $validIntervals = [1, 2, 3, 4, 6, 12, 60];
                    } elseif ($timeSamplingChoice === 'daily') {
                        $validIntervals = [1, 2, 4, 8];
                    } elseif ($timeSamplingChoice === 'monthly') {
                        $validIntervals = [1, 2];
                    } else {
                        $fail('Invalid time_sampling_choice value. (hourly, daily ó monthly)');
                        return;
                    }
                    if (!in_array($value, $validIntervals)) {
                        $fail('Invalid data_per_interval value for the selected time_sampling_choice. hourly->(1, 2, 3, 4, 6, 12, 60) daily->(1, 2, 4, 8) monthly->(1, 2)');
                    }
                },
            ],
            'data_per_seconds' => ['required', 'numeric', 'between:0,254']
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setWifiCredentialsForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
            'ssid' => 'required | string',
            'password' => 'required | string',
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setBrokerCredentialsForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
            'host' => 'required | string',
            'port' => 'required | string',
            'user' => 'required | string',
            'password' => 'required | string',
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setDateForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getDateForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setStatusCoilForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
            'status' => 'required | boolean'
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getStatusCoilForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setTypeSensorForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
            'type' => [
                'required',
                'in:1,2,3',
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getTypeSensorForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getStatusSensorForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getStatusConnectionForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getCurrentReadingsForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function OnOffRealTimeForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
            'status' => 'required | boolean'

        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function otaUpdate($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
            'version' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }

    public function setBillingDay($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
            'billing_day' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setStatusServiceCoil($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
            'status_service_coil' => 'required | boolean'

        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setPasswordMeter($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ],
            'password' => ['required', 'string', 'max:21'],

        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getPasswordMeter($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $this->serialValidationLogic($attribute, $value, $fail, $request);
                },
            ]

        ]);
        if ($validator->fails()) {
            return $this->setErrorMessage($validator, $request);
        }
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }

    private function setErrorMessage($validator, $request){
        $errors = $validator->messages();
        $jsonError = ErrorResource::make(["code" => 400, "message" => "La solicitud enviada al servidor es incorrecta o no se puede procesar", "details" => $errors]);
        $eventLog = EventLog::find(json_decode($request->header(EventLog::EVENT_LOG_HEADER), true)["id"]);
        $eventLog->status = EventLog::STATUS_ERROR;
        $eventLog->response_json = json_encode($jsonError);
        $eventLog->save();
        $ackLog = $eventLog->ackLog;
        $ackLog->status = AckLog::STATUS_EXPIRED;
        $ackLog->save();
        return $jsonError;
    }
}
