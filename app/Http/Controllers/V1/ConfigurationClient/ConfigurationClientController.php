<?php

namespace App\Http\Controllers\V1\ConfigurationClient;


use App\Http\Controllers\V1\Controller;
use App\Http\Requests\V1\OnOffRealTimeRequest;
use App\Http\Requests\V1\OtaUpdateRequest;
use App\Http\Requests\V1\SetAlertLimitsRequest;
use App\Http\Requests\V1\SetAlertTimeRequest;
use App\Http\Requests\V1\SetBillingDayRequest;
use App\Http\Requests\V1\SetBrokerCredentialsRequest;
use App\Http\Requests\V1\SetControlLimitsRequest;
use App\Http\Requests\V1\SetControlStatusRequest;
use App\Http\Requests\V1\SetPasswordMeterRequest;
use App\Http\Requests\V1\SetSamplingTimeRequest;
use App\Http\Requests\V1\SetStatusCoilRequest;
use App\Http\Requests\V1\SetStatusServiceCoilRequest;
use App\Http\Requests\V1\SetTypeSensorRequest;
use App\Http\Requests\V1\SetWifiCredentialsRequest;
use App\Http\Requests\V1\ValidateSerialRequest;
use App\Http\Services\V1\ConfigurationClient\ConfigurationClientService;
use App\Models\V1\Api\EventLog;
use App\Models\V1\ClientAlert;
use App\ModulesAux\MQTT;
use App\Rules\ValidateSerialRule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationClientController extends Controller
{
    protected $configurationClientService;

    public function __construct(ConfigurationClientService $configurationClientService)
    {
        $this->configurationClientService = $configurationClientService;
    }

    public function setAlertLimitsForSerial(SetAlertLimitsRequest $request): JsonResource
    {
        return $this->configurationClientService->setAlertLimitsForSerial($request);
    }

    public function setControlLimitsForSerial(SetControlLimitsRequest $request): JsonResource
    {
        return $this->configurationClientService->setControlLimitsForSerial($request);
    }

    public function setControlStatusForSerial(SetControlStatusRequest $request): JsonResource
    {
        return $this->configurationClientService->setControlStatusForSerial($request);
    }

    public function setAlertTimeForSerial(SetAlertTimeRequest $request): JsonResource
    {
        return $this->configurationClientService->setAlertTimeForSerial($request);
    }

    public function setSamplingTimeForSerial(SetSamplingTimeRequest $request): JsonResource
    {
        return $this->configurationClientService->setSamplingTimeForSerial($request);
    }

    public function setWifiCredentialsForSerial(SetWifiCredentialsRequest $request): JsonResource
    {
        return $this->configurationClientService->setWifiCredentialsForSerial($request);
    }

    public function setBrokerCredentialsForSerial(SetBrokerCredentialsRequest $request): JsonResource
    {
        return $this->configurationClientService->setBrokerCredentialsForSerial($request);
    }

    public function setDateForSerial(ValidateSerialRequest $request): JsonResource
    {
        return $this->configurationClientService->setDateForSerial($request);
    }
    public function getDateForSerial(ValidateSerialRequest $request): JsonResource
    {
        return $this->configurationClientService->getDateForSerial($request);
    }
    public function setStatusCoilForSerial(SetStatusCoilRequest $request): JsonResource
    {
        return $this->configurationClientService->setStatusCoilForSerial($request);
    }
    public function getStatusCoilForSerial(ValidateSerialRequest $request): JsonResource
    {
        return $this->configurationClientService->getStatusCoilForSerial($request);
    }
    public function setTypeSensorForSerial(SetTypeSensorRequest $request): JsonResource
    {
        return $this->configurationClientService->setTypeSensorForSerial($request);
    }
    public function getTypeSensorForSerial(ValidateSerialRequest $request): JsonResource
    {
        return $this->configurationClientService->getTypeSensorForSerial($request);
    }
    public function getStatusSensorForSerial(ValidateSerialRequest $request): JsonResource
    {
        return $this->configurationClientService->getStatusSensorForSerial($request);
    }
    public function getStatusConnectionForSerial(ValidateSerialRequest $request): JsonResource
    {
        return $this->configurationClientService->getStatusConnectionForSerial($request);
    }
    public function getCurrentReadingsForSerial(ValidateSerialRequest $request): JsonResource
    {
        return $this->configurationClientService->getCurrentReadingsForSerial($request);
    }
    public function OnOffRealTimeForSerial(OnOffRealTimeRequest $request): JsonResource
    {
        return $this->configurationClientService->OnOffRealTimeForSerial($request);
    }
    public function otaUpdate(OtaUpdateRequest $request): JsonResource
    {
        return $this->configurationClientService->otaUpdate($request);
    }
    public function setBillingDay(SetBillingDayRequest $request): JsonResource
    {
        return $this->configurationClientService->setBillingDay($request);
    }
    public function setStatusServiceCoil(SetStatusServiceCoilRequest $request): JsonResource
    {
        return $this->configurationClientService->setStatusServiceCoil($request);
    }
    public function setPasswordMeter(SetPasswordMeterRequest $request): JsonResource
    {
        return $this->configurationClientService->setPasswordMeter($request);
    }
    public function getPasswordMeter(ValidateSerialRequest $request): JsonResource
    {
        return $this->configurationClientService->getPasswordMeter($request);
    }


    public function notificationWebhook(Request $request)
    {
        $datosJson = $request->json()->all();
        $event = EventLog::find($datosJson['id_event']);
        $responseData = [
            'status' => 'success',
            'message' => 'Webhook procesado exitosamente',
            'request_json' => $datosJson
        ];
        $mqtt = MQTT::connection('default', 'aom-channel-'.$datosJson['serial'].$datosJson['id_event']);
        $mqtt->publish('aom/chanel', json_encode($datosJson));
        $mqtt->disconnect();
        $alert = ClientAlert::whereEventLogId($event->id)->first();
        if ($alert == null){
            $alertGenerated = ClientAlert::create([
                'client_id' => $event->client_id,
                'microcontroller_data_id' => null,
                'client_alert_configuration_id' => null,
                'value' => null,
                'type' => ClientAlert::INFORMATIVE,
                'source_timestamp' => $event->created_at->format('Y-m-d H:i:s'),
                'event_log_id' => $event->id
            ]);
        }
        // Retornar una instancia de Response con los datos y código de estado apropiados
        return response()->json($responseData, 200);
    }

}
