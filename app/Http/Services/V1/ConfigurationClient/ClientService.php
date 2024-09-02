<?php

namespace App\Http\Services\V1\ConfigurationClient;

use App\Http\Repositories\Client\ClientRepository;
use App\Http\Resources\Json\V1\ClientResource;
use App\Http\Resources\Json\V1\ErrorResource;
use App\Http\Resources\Json\V1\Data\DataResource;
use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\User;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function addClient($request): JsonResource
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
                            $user = User::getUserModelApi($key);
                            if ($equipment->assigned || $equipment->has_clients) {
                                    $fail("El medidor electrico con serial " . $value . " ya esta asociado a otro cliente");
                                }

                        }
                    }
                },
            ]
        ]);
        if ($validator->fails()) {
            $errors = $validator->messages();
            $jsonError = ErrorResource::make(["code" => 400, "message" => " La solicitud enviada al servidor es incorrecta o no se puede procesar", "details" => $errors]);
            $eventLog = EventLog::find(json_decode($request->header(EventLog::EVENT_LOG_HEADER), true)["id"]);
            $eventLog->status = EventLog::STATUS_ERROR;
            $eventLog->response_json = json_encode($jsonError);
            $eventLog->save();
            $ackLog = $eventLog->ackLog;
            $ackLog->status = AckLog::STATUS_EXPIRED;
            $ackLog->save();
            return $jsonError;
        }
        $response = ClientResource::collection($this->clientRepository->addClient($request));
        $eventLog = EventLog::find($this->getEventLogId());
        $eventLog->update([
            "status" => EventLog::STATUS_SUCCESSFUL,
            "response_json" => json_encode($response)
        ]);
        $eventLog->ackLog->update([
            "status" => AckLog::STATUS_SUCCESS,
        ]);

        return $response;
    }
    public function getEventLogId()
    {
        return json_decode(Request::header(EventLog::EVENT_LOG_HEADER), true)["id"];
    }

    public function getDateRangeSerial($request): JsonResource
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
                            $key = \App\Models\V1\ApiKey::where('api_key', $request->header('x-api-key'))->first();
                            $user = User::getUserModel($key);
                            if ($equipment->network_operator_id !== $user->id) {
                                $fail("El medidor electrico con serial " . $value . " no pertenece a su organización");
                            } else {
                                $client = $equipment->clients()->first();
                                if($client == null){
                                    $fail("El medidor electrico con serial " . $value . " no a sido asignado a ningun cliente");
                                }
                            }
                        }
                    }
                },
            ],
            'fecha_inicio' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value) && !DateTime::createFromFormat('Y-m-d H:i:s', $value)) {
                        $fail("El campo $attribute debe ser una fecha válida en formato Unix o 'Y-M-D H:i:s'.");
                    }
                },
            ],
            'fecha_fin' => [
                'required',
                'different:fecha_inicio', // La fecha fin debe ser diferente a la fecha de inicio
                function ($attribute, $value, $fail) use ($request) {
                    if (!is_numeric($value) && !DateTime::createFromFormat('Y-m-d H:i:s', $value)) {
                        $fail("El campo $attribute debe ser una fecha válida en formato Unix o 'Y-M-D H:i:s'.");
                    }
                    $fechaInicio = $request->input('fecha_inicio');
                    if (is_numeric($fechaInicio) && is_numeric($value)) {
                        if ($fechaInicio >= $value) {
                            $fail("El campo $attribute debe ser una fecha posterior a la fecha de inicio.");
                        }
                    } elseif (DateTime::createFromFormat('Y-m-d H:i:s', $fechaInicio) && DateTime::createFromFormat('Y-m-d H:i:s', $value)) {
                        $fechaInicioObj = DateTime::createFromFormat('Y-m-d H:i:s', $fechaInicio);
                        $fechaFinObj = DateTime::createFromFormat('Y-m-d H:i:s', $value);
                        if ($fechaInicioObj >= $fechaFinObj) {
                            $fail("El campo $attribute debe ser una fecha posterior a la fecha de inicio.");
                        }
                    }
                },
            ],
        ]);


        $serial = $request->query('serial');
        $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
        $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
            ->where('serial', $serial)->first();
        $client = $equipment->clients()->first();

        return DataResource::collection($this->clientRepository->getDateRangeClientDataPaginate($request, $client->id));
    }
}
