<?php

namespace App\Http\Controllers\V1\ConfigurationClient;


use App\Http\Controllers\V1\Controller;
use App\Http\Services\V1\ConfigurationClient\ConfigurationClientService;
use App\ModulesAux\MQTT;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationClientController extends Controller
{
    protected $configurationClientService;

    public function __construct(ConfigurationClientService $configurationClientService)
    {
        $this->configurationClientService = $configurationClientService;
    }
    public function setAlertLimitsForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setAlertLimitsForSerial($request);
    }
    public function setControlLimitsForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setControlLimitsForSerial($request);
    }
    public function setControlStatusForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setControlStatusForSerial($request);
    }
    public function setAlertTimeForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setAlertTimeForSerial($request);
    }
    public function setSamplingTimeForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setSamplingTimeForSerial($request);
    }
    public function setWifiCredentialsForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setWifiCredentialsForSerial($request);
    }
    public function setBrokerCredentialsForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setBrokerCredentialsForSerial($request);
    }
    public function setDateForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setDateForSerial($request);
    }
    public function getDateForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->getDateForSerial($request);
    }
    public function setStatusCoilForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setStatusCoilForSerial($request);
    }
    public function getStatusCoilForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->getStatusCoilForSerial($request);
    }
    public function setTypeSensorForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setTypeSensorForSerial($request);
    }
    public function getTypeSensorForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->getTypeSensorForSerial($request);
    }
    public function getStatusSensorForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->getStatusSensorForSerial($request);
    }
    public function getStatusConnectionForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->getStatusConnectionForSerial($request);
    }
    public function getCurrentReadingsForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->getCurrentReadingsForSerial($request);
    }
    public function OnOffRealTimeForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->OnOffRealTimeForSerial($request);
    }
    public function otaUpdate(Request $request): JsonResource
    {
        return $this->configurationClientService->otaUpdate($request);
    }
    public function setBillingDay(Request $request): JsonResource
    {
        return $this->configurationClientService->setBillingDay($request);
    }
    public function setStatusServiceCoil(Request $request): JsonResource
    {
        return $this->configurationClientService->setStatusServiceCoil($request);
    }
    public function setPasswordMeter(Request $request): JsonResource
    {
        return $this->configurationClientService->setPasswordMeter($request);
    }
    public function getPasswordMeter(Request $request): JsonResource
    {
        return $this->configurationClientService->getPasswordMeter($request);
    }


    public function notificationWebhook(Request $request)
    {
        $datosJson = $request->json()->all();

        $responseData = [
            'status' => 'success',
            'message' => 'Webhook procesado exitosamente',
            'request_json' => $datosJson
        ];
        $mqtt = MQTT::connection('default', 'null');
        $mqtt->publish('aom/chanel', json_encode($datosJson));
        $mqtt->disconnect();
        // Retornar una instancia de Response con los datos y código de estado apropiados
        return response()->json($responseData, 200);
    }

}
