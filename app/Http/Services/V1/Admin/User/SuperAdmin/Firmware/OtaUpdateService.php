<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\Singleton;
use App\Models\Model\V1\Firmware;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\SuperAdmin;
use App\ModulesAux\MQTT;
use App\Strategy\MqttSenderPattern\FetchDataApiStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpMqtt\Client\Exceptions\MqttClientException;

class OtaUpdateService extends Singleton
{
    public function mount(Component $component, Firmware $model)
    {
        $component->fill([
            "meters" => [],
            'picked' => false,
            'status' => false,
            'model' => $model,
            'meter_id' => null,
            'progress' => 0
        ]);

    }
    public function updatedMeter(Component $component)
    {
        $component->meter_picked = false;
        $component->meter_id = null;
        $component->message_meter = "No se encontraron meteres para este filtro";
        if ($component->meter != "") {
            $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
            $component->meters = $equipment_type->equipment()
                ->where(function (Builder $query) use ($component) {
                    return $query->where("serial", "like", '%' . $component->meter . "%");
                })->take(5)->get();
        }
    }
    public function assignMeter(Component $component, $meter)
    {
        $obj = json_decode($meter);
        $component->meter = $obj->serial . " - " . $obj->name;
        $component->meter_id = $obj->id;
        $component->picked = true;
        $component->meter_picked = true;
    }

    public function submitForm(Component $component)
    {
        if ($component->status){
            $component->emitTo('livewire-toast', 'error', "Se encuentra en ejecución");
            return;
        }
        if ($component->meter_id == null or $component->meter_id == ''){
            $component->emitTo('livewire-toast', 'error', "Debe seleccionar un medidor existente");
            return;
        }
        $equipment = Equipment::find($component->meter_id);
        if ($equipment == null){
            $component->emitTo('livewire-toast', 'error', "Debe seleccionar un medidor existente");
            return;
        }
        $firmware = Firmware::find($component->model->id);
        if($firmware == null){
            $component->emitTo('livewire-toast', 'error', "El firmware seleccionado no tiene archivo relacionado");
            return;
        }
        $file = $firmware->evidence();
        if($file == null){
            $component->emitTo('livewire-toast', 'error', "El firmware seleccionado no tiene archivo relacionado");
            return;
        }
        $filePath = $firmware->downloadFileFromS3($file->path);
        $fileSize=filesize($filePath);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        $allowedMimeTypes = [
            'application/octet-stream',
            'application/x-dosexec',
        ];
        $maxFileSize = 2048 * 1024; // 2MB en bytes
        if (in_array($mimeType, $allowedMimeTypes) && $fileSize <= $maxFileSize) {
            $apiKey =ApiKey::first();
            $requestDetails = [
                'url' => 'https://aom.enerteclatam.com/api/v1/config/ota-update',
                'method' => 'POST',
                'body' => [
                    'serial' => $equipment->serial,
                    'version' => $component->model->id
                ],
                'apiKey' => $apiKey->api_key
            ];
            try {
                $mqtt = MQTT::connection('default', EventLog::EVENT_OTA_UPDATE.'-'.$equipment->serial.'aom-channel');
                $mqttCoilAckStrategy = new FetchDataApiStrategy($mqtt, $component);
                $mqttCoilAckStrategy->fetchDataFromAPI($requestDetails);
                $mqttCoilAckStrategy->registerLoopEventHandler();
                $mqttCoilAckStrategy->subscribe($equipment, 43);
            } catch (MqttClientException $e) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Intente nuevamente"]);
            }

        } else {
            $component->emitTo('livewire-toast', 'error', "El archivo no cumple con las condiciones requeridas.");
        }
    }
}
