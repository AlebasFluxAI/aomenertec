<?php

namespace App\Jobs\V1\Enertec;

use App\Events\RealTimeMonitoringEvent;
use App\Models\V1\Client;
use App\Models\V1\EquipmentClient;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushRealTimeMicrocontrollerDataJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $raw_json;

    public function __construct($raw_json)
    {
        $this->raw_json = $raw_json;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Logica para envio de eventos a fornt por livewire.
        $data = $this->unpackData();
        event(new RealTimeMonitoringEvent($data));
    }
    private function unpackData()
    {
        $data_frame = config('data-frame.data_frame');
        $decode = bin2hex(base64_decode($this->raw_json));
        //$decode = $this->raw_json;

        foreach ($data_frame as $data) {
            try {
                $split = substr($decode, ($data['start']), ($data['lenght']));
                $bin = hex2bin($split);
                $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                if (is_nan($json[$data['variable_name']])) {
                    $json[$data['variable_name']] = null;
                }
                if ($data['variable_name'] == "equipment_id") {
                    $equipment_serial = $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "timestamp") {
                    $timestamp_unix = $json[$data['variable_name']];
                }
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            }
        }
        $current_time = new \DateTime("@$timestamp_unix");
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
            ->first();
        $aux = EquipmentClient::whereEquipmentId($equipment->id)->whereCurrentAssigned(true)->first();
        $client_id= $aux->client_id;
        $json['timestamp'] = $current_time->format('Y-m-d H:i:s');
        $json['client_id'] = $client_id;
        return $json;
    }
}
