<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\Singleton;
use App\Models\Model\V1\Firmware;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
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
        ]);
        $file = $component->model->evidences()->first();
        if($file){
            $component->file = $file->url;
        }
    }
    public function updatedClient(Component $component)
    {
        $component->meter_picked = false;
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
        $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
        $equipment = $equipment_type->equipment()->whereSerial($component->meter)->first();
        if ($equipment == null){
            $component->emitTo('livewire-toast', 'error', "Debe seleccionar un medidor existente");
            return;
        }
        $firmware = $component->firmware->evidence();
        if($firmware == null){
            $component->emitTo('livewire-toast', 'error', "El firmware seleccionado no tiene archivo relacionado");
            return;
        }
        $filePath = $firmware->downloadFileFromS3($firmware->evidence()->path);
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
            // El archivo es válido y puede procesarse
            echo "El archivo es válido.";
        } else {
            $component->emitTo('livewire-toast', 'error', "El archivo no cumple con las condiciones requeridas.");
            return;
        }
        $apiKey =ApiKey::first();
        $requestDetails = [
            'url' => 'https://aom.enerteclatam.com/api/v1/config/ota-update',
            'method' => 'POST',
            'body' => [
                'serial' => $equipment->serial,
                'version' => $component->firmware->id
            ],
            'apiKey' => $apiKey->api_key
        ];
        try {
            $mqtt = MQTT::connection('default', EventLog::EVENT_OTA_UPDATE.'-'.$equipment->serial.'aom-channel');
            $mqttCoilAckStrategy = new FetchDataApiStrategy($mqtt, $this);
            $mqttCoilAckStrategy->fetchDataFromAPI($requestDetails);
            $mqttCoilAckStrategy->registerLoopEventHandler();
            $mqttCoilAckStrategy->subscribe($equipment, 43);
        } catch (MqttClientException $e) {
            $this->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Intente nuevamente"]);
        }
    }
    public function initUpload(Component $component)
    {
        $firmware = $component->firmware->evidence();
        if($firmware == null){
            $component->emitTo('livewire-toast', 'error', "El firmware seleccionado no tiene archivo relacionado");
            return;
        }
        $filePath = $firmware->downloadFileFromS3($firmware->evidence()->path);
        $fileSize=filesize($filePath);
        $total_frame = ceil($fileSize/320);
        for ($i = 1; $i <= $total_frame; $i++) {

            $this->progress = round(($i / $total_frame) * 100);
            usleep(60000); // 50ms
            $this->emit('updateProgress');
        }

    }
}
