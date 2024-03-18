<?php

namespace App\Jobs\V1\Api\ConfigurationClient;

use App\Models\V1\Client;
use App\Models\V1\Api\EventLog;
use App\ModulesAux\MQTT;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
class FirmwareUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $json;
    public function __construct($json)
    {
        $this->json = $json;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo $this->json['status']."\n";
        if ($this->json['status'] == 0){
            return;
        }
        if(array_key_exists('id_event_log', $this->json)) {
            $eventLog = EventLog::find($this->json['id_event_log']);
            if ($eventLog == null) {
                return;
            }
            $requestJson = json_decode($eventLog->request_json);
            if (array_key_exists('serial', $this->json)) {
                $client = Client::getClientFromSerial($this->json['serial']);
                if ($client == null) {
                    return;
                }
                $pathFile= $requestJson->path_file;
                $file = fopen(storage_path('app/'.$pathFile), 'rb');
                $i = 0;
                $mqtt = MQTT::connection("default", "null");

                while (!feof($file)) {
                    try {
                        if (!$mqtt->isConnected()) {
                            $mqtt = MQTT::connection("default", "null");
                        }
                        $bloque = fread($file, 320);
                        $mqtt->publish('v1/mc/ota/'.$this->json['serial'], $bloque);
                        echo $i."\n";
                        $i++;
                        usleep(20000);

                    } catch (\PhpMqtt\Client\Exceptions\DataTransferException $e) {
                        echo "fail ".$i."\n";
                        sleep(2);
                        $mqtt = MQTT::connection("default", "null");
                        if (!$mqtt->isConnected()) {
                            $mqtt = MQTT::connection("default", "null");
                        }
                        $bloque = fread($file, 320);
                        $mqtt->publish('v1/mc/ota/'.$this->json['serial'], $bloque);
                        $i++;
                        usleep(20000);
                    }
                }
                $mqtt->disconnect();

                fclose($file);
                if (Storage::exists($pathFile)) {
                    Storage::delete($pathFile);
//                    if (!Storage::exists($pathFile)) {
//                        // El archivo se eliminó correctamente.
//                        return response()->json(['mensaje' => 'El archivo se eliminó con éxito.']);
//                    } else {
//                        // Manejar el caso en el que el archivo no se pudo eliminar.
//                        return response()->json(['error' => 'No se pudo eliminar el archivo.'], 500);
//                    }
                }
            }
        }
    }
}
