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


    public function setAlertLimitsForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setControlLimitsForSerial($request): JsonResource
    {
       return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setControlStatusForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setAlertTimeForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setSamplingTimeForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setWifiCredentialsForSerial($request): JsonResource
    {
       return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setBrokerCredentialsForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setDateForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getDateForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setStatusCoilForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getStatusCoilForSerial($request): JsonResource
    {
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
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getStatusSensorForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getStatusConnectionForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getCurrentReadingsForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function OnOffRealTimeForSerial($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function otaUpdate($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }

    public function setBillingDay($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setStatusServiceCoil($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function setPasswordMeter($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
    public function getPasswordMeter($request): JsonResource
    {
        return ConfigurationDefaultResponseResource::make($this->configurationClientRepository->runService());
    }
}
