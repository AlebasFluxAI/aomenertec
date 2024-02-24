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
use Illuminate\Support\Facades\Validator;

class ConfigurationClientService
{
    protected $configurationClientRepository;

    public function __construct(ConfigClientRepository $configurationClientRepository)
    {
        $this->configurationClientRepository = $configurationClientRepository;
    }

    public function setStatusCoilForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
                    if ($equipment_type != null) {
                        $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
                            ->where('serial', $value)->first();
                        if ($equipment == null) {
                            $fail("El medidor electrico con serial " . $value . " no existe");
                        } else {
                            $key = ApiKey::where('api_key', $request->header('x-api-key'))->first();
                            $user = User::getUserModel($key);
                            if ($equipment->network_operator_id !== $user->id) {
                                $fail("El medidor electrico con serial " . $value . " no pertenece a su organización");
                            } else {
                                $client = $equipment->clients()->first();
                                if ($client == null) {
                                    $fail("El medidor electrico con serial " . $value . " no a sido asignado a ningun cliente");
                                }
                            }
                        }
                    }
                },
            ],
            'status' => 'required | boolean'
        ]);
        if ($validator->fails()) {
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
        //OK
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->setStatusCoilForSerial());
    }

    public function getStatusCoilForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
                    if ($equipment_type != null) {
                        $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
                            ->where('serial', $value)->first();
                        if ($equipment == null) {
                            $fail("El medidor electrico con serial " . $value . " no existe");
                        } else {
                            $key = ApiKey::where('api_key', $request->header('x-api-key'))->first();
                            $user = User::getUserModel($key);
                            if ($equipment->network_operator_id !== $user->id) {
                                $fail("El medidor electrico con serial " . $value . " no pertenece a su organización");
                            } else {
                                $client = $equipment->clients()->first();
                                if ($client == null) {
                                    $fail("El medidor electrico con serial " . $value . " no a sido asignado a ningun cliente");
                                }
                            }
                        }
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
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
        //OK
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->getStatusCoilForSerial());
    }

    public function setDateForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
                    if ($equipment_type != null) {
                        $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
                            ->where('serial', $value)->first();
                        if ($equipment == null) {
                            $fail("El medidor electrico con serial " . $value . " no existe");
                        } else {
                            $key = ApiKey::where('api_key', $request->header('x-api-key'))->first();
                            $user = User::getUserModel($key);
                            if ($equipment->network_operator_id !== $user->id) {
                                $fail("El medidor electrico con serial " . $value . " no pertenece a su organización");
                            } else {
                                $client = $equipment->clients()->first();
                                if ($client == null) {
                                    $fail("El medidor electrico con serial " . $value . " no a sido asignado a ningun cliente");
                                }
                            }
                        }
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
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
        //OK
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->setDateForSerial());
    }

    public function getDateForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
                    if ($equipment_type != null) {
                        $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
                            ->where('serial', $value)->first();
                        if ($equipment == null) {
                            $fail("El medidor electrico con serial " . $value . " no existe");
                        } else {
                            $key = ApiKey::where('api_key', $request->header('x-api-key'))->first();
                            $user = User::getUserModel($key);
                            if ($equipment->network_operator_id !== $user->id) {
                                $fail("El medidor electrico con serial " . $value . " no pertenece a su organización");
                            } else {
                                $client = $equipment->clients()->first();
                                if ($client == null) {
                                    $fail("El medidor electrico con serial " . $value . " no a sido asignado a ningun cliente");
                                }
                            }
                        }
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
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
        //OK
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->getDateForSerial());
    }

    public function getTypeSensorForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
                    if ($equipment_type != null) {
                        $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
                            ->where('serial', $value)->first();
                        if ($equipment == null) {
                            $fail("El medidor electrico con serial " . $value . " no existe");
                        } else {
                            $key = ApiKey::where('api_key', $request->header('x-api-key'))->first();
                            $user = User::getUserModel($key);
                            if ($equipment->network_operator_id !== $user->id) {
                                $fail("El medidor electrico con serial " . $value . " no pertenece a su organización");
                            } else {
                                $client = $equipment->clients()->first();
                                if ($client == null) {
                                    $fail("El medidor electrico con serial " . $value . " no a sido asignado a ningun cliente");
                                }
                            }
                        }
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
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
        //OK
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->getTypeSensorForSerial());
    }

    public function setTypeSensorForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
                    if ($equipment_type != null) {
                        $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
                            ->where('serial', $value)->first();
                        if ($equipment == null) {
                            $fail("El medidor electrico con serial " . $value . " no existe");
                        } else {
                            $key = ApiKey::where('api_key', $request->header('x-api-key'))->first();
                            $user = User::getUserModel($key);
                            if ($equipment->network_operator_id !== $user->id) {
                                $fail("El medidor electrico con serial " . $value . " no pertenece a su organización");
                            } else {
                                $client = $equipment->clients()->first();
                                if ($client == null) {
                                    $fail("El medidor electrico con serial " . $value . " no a sido asignado a ningun cliente");
                                }
                            }
                        }
                    }
                },
            ],
            'type' => [
                'required',
                'in:1,2,3',
            ],
        ]);
        if ($validator->fails()) {
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
        //OK
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->setTypeSensorForSerial());
    }

    public function getStatusSensorForSerial($request): JsonResource
    {
        $validator = Validator::make($request->all(), [
            'serial' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
                    if ($equipment_type != null) {
                        $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
                            ->where('serial', $value)->first();
                        if ($equipment == null) {
                            $fail("El medidor electrico con serial " . $value . " no existe");
                        } else {
                            $key = ApiKey::where('api_key', $request->header('x-api-key'))->first();
                            $user = User::getUserModel($key);
                            if ($equipment->network_operator_id !== $user->id) {
                                $fail("El medidor electrico con serial " . $value . " no pertenece a su organización");
                            } else {
                                $client = $equipment->clients()->first();
                                if ($client == null) {
                                    $fail("El medidor electrico con serial " . $value . " no a sido asignado a ningun cliente");
                                }
                            }
                        }
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
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
        //OK
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->getStatusSensorForSerial());
    }
}
