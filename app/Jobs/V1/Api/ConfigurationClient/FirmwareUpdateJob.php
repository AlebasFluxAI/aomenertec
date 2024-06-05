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
    public $timeout = 300;
    public $json;
    public $i;
    public $j;
    public function __construct($json, $i, $j)
    {
        $this->json = $json;
        $this->i = $i;
        $this->j = $j;
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
                $filePath = $eventLog->downloadFileFromS3($eventLog->evidences[0]->path);
                $fileSize=filesize($filePath);
                $j=$this->j;
                $aux= floor($fileSize/(320*8))*$j;
                $file = fopen($filePath, 'rb');
                $i=$this->i;
                if (file_exists($filePath)) {
                    $file = fopen($filePath, 'rb');
                    $i = 0;
                    if ($file) {
                        $mqtt = MQTT::connection("default", "null");
                        while (!feof($file)) {
                            if ($i == ($aux)){
                                $j++;
                                dispatch(new FirmwareUpdateJob($this->json,$i,$j))->onQueue('spot3');
                                break;
                            }
                            if ($i < $aux) {

                                try {
                                    if (!$mqtt->isConnected()) {
                                        $mqtt = MQTT::connection("default", "null");
                                    }
                                    $bloque = fread($file, 320);
                                    $mqtt->publish('v1/mc/ota/' . $this->json['serial'], $bloque);
                                    echo $i . "\n";
                                    $i++;
                                    usleep(50000);

                                } catch (\PhpMqtt\Client\Exceptions\DataTransferException $e) {
                                    echo "fail " . $i . "\n";
                                    sleep(2);
                                    $mqtt = MQTT::connection("default", "null");
                                    if (!$mqtt->isConnected()) {
                                        $mqtt = MQTT::connection("default", "null");
                                    }
                                    $bloque = fread($file, 320);
                                    $mqtt->publish('v1/mc/ota/' . $this->json['serial'], $bloque);
                                    $i++;
                                    usleep(20000);
                                }
                            } else{
                                $i++;
                            }
                        }
                        $mqtt->disconnect();
                        fclose($file);
                    }
                }
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
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
