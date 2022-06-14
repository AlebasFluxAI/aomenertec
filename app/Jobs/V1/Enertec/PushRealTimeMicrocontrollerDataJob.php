<?php

namespace App\Jobs\V1\Enertec;

use App\Events\RealTimeMonitoringEvent;
use App\Models\V1\Client;
use App\Models\V1\EquipmentClient;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
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
        $data = $this->unpackData();
        event(new RealTimeMonitoringEvent($data));
    }
    private function unpackData()
    {
        $data_frame = config('data-frame.data_frame');
        $decode = bin2hex(base64_decode($this->raw_json));
        foreach ($data_frame as $data) {
            try {
                $split = substr($decode, ($data['start']), ($data['lenght']));
                $bin = hex2bin($split);
                if ($data['variable_name'] == 'flags'){
                    $json[$data['variable_name']] = 0;
                } elseif ($data['variable_name'] == 'timestamp' || $data['variable_name'] == 'network_operator_id' || $data['variable_name'] == 'equipment_id'){
                    $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                } else{
                    $json[$data['variable_name']] = round(unpack($data['type'], $bin)[1], 3);
                }
                if ($data['start'] >= 432) {
                    break;
                }
                if (is_nan($json[$data['variable_name']])) {
                    $json[$data['variable_name']] = null;
                }
            } catch (Exception $e) {
                echo 'Excepción capturada: ', $e->getMessage(), "\n";
            }
        }

        $current_time = Carbon::now()->format('Y-m-d H:i:s');
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($json['equipment_id'])
            ->first();
        $client = $equipment->clients()->first();
        $client_id = $client->id;
        $json['timestamp'] = $current_time;
        $json['client_id'] = $client_id;
        return $json;
    }
}
