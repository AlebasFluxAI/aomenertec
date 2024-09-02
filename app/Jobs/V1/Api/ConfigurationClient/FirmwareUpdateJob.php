<?php

namespace App\Jobs\V1\Api\ConfigurationClient;

use App\Events\setProgressOtaUploadEvent;
use App\Models\Model\V1\Firmware;
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
                $firmware = Firmware::find($requestJson->version);
                $filePath = $firmware->downloadFileFromS3($firmware->evidence()->path);
                $fileSize=filesize($filePath);
                $j=$this->j;
                $total_frame = $fileSize/320;
                $aux= floor($fileSize/(320*8))*$j;
                $i=0;
                $k=0;
                echo $aux. " - " .$this->j. " - ".$this->i." - ".$total_frame."\n";
                if (file_exists($filePath)) {
                    $file = fopen($filePath, 'rb');
                    if ($file) {
                        $mqtt = MQTT::connection("default", "null");
                        while (!feof($file)) {
                            $bloque = fread($file, 320);
                            if ($i == ($aux)){
                                $j++;
                                dispatch(new FirmwareUpdateJob($this->json,$i,$j))->onQueue('spot3');
                                break;
                            }
                            if ($i < $aux && $i >= $this->i ) {
                                try {
                                    if (!$mqtt->isConnected()) {
                                       $mqtt = MQTT::connection("default", "null");
                                    }
                                    $mqtt->publish('v1/mc/ota/' . $this->json['serial'], $bloque);
                                    $progress = round(($i / $total_frame) * 100);
                                    if ($k == 50){
                                        event(new setProgressOtaUploadEvent($progress, $firmware->id));
                                        $k=0;
                                    }if ($progress == 100){
                                        event(new setProgressOtaUploadEvent($progress, $firmware->id));
                                        $k=0;
                                    }
                                    echo $i."\n";
                                    $i++;
                                    $k++;
                                    usleep(50000);

                                } catch (\PhpMqtt\Client\Exceptions\DataTransferException $e) {
                                    echo "fail " . $i . "\n";
                                    sleep(2);
                                    $mqtt = MQTT::connection("default", "null");
                                    if (!$mqtt->isConnected()) {
                                        $mqtt = MQTT::connection("default", "null");
                                    }
                                    $mqtt->publish('v1/mc/ota/' . $this->json['serial'], $bloque);
                                    $i++;
                                    usleep(50000);
                                }

                            } else{
                                $i++;
                            }

                        }
                        $mqtt->disconnect();
                        fclose($file);
                    }
                }
            }
        }
    }
}
