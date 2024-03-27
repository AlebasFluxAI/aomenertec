<?php

namespace App\Jobs\V1\Api\ConfigurationClient;

use App\Models\V1\Api\AckLog;
use App\Models\V1\Client;
use App\Models\V1\Api\EventLog;
use App\ModulesAux\MQTT;

use Carbon\Carbon;
use Crc16\Crc16;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Request;

class SendReactiveDataMcJob implements ShouldQueue
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

        if(array_key_exists('serial', $this->json)) {

            $client = Client::getClientFromSerial($this->json['serial']);
            if ($client == null){
                return;
            }
            $last_data = $client->microcontrollerData()->select('raw_json')->orderBy('source_timestamp', 'desc')->first();
            if ($last_data == null){
                return;
            }
            $raw_json = json_decode($last_data->raw_json, true);

            if ($raw_json != null) {
                $this->json['l1_import_kvarLh'] = $raw_json['ph1_varLh_acumm'];
                $this->json['l2_import_kvarLh'] = $raw_json['ph2_varLh_acumm'];
                $this->json['l3_import_kvarLh'] = $raw_json['ph3_varLh_acumm'];
                $this->json['l1_import_kvarCh'] = $raw_json['ph1_varCh_acumm'];
                $this->json['l2_import_kvarCh'] = $raw_json['ph2_varCh_acumm'];
                $this->json['l3_import_kvarCh'] = $raw_json['ph3_varCh_acumm'];
            } else{
                $this->json['l1_import_kvarLh'] = 0;
                $this->json['l2_import_kvarLh'] = 0;
                $this->json['l3_import_kvarLh'] = 0;
                $this->json['l1_import_kvarCh'] = 0;
                $this->json['l2_import_kvarCh'] = 0;
                $this->json['l3_import_kvarCh'] = 0;
            }
            $message = $this->packMessage(29);
            if ($message != ''){
                echo 'v1/mc/config/'.$this->json['serial']."\n";
                $mqtt = MQTT::connection("default", "reactivos");
                $mqtt->publish('v1/mc/config/'.$this->json['serial'], $message);
                $mqtt->disconnect();
            }
        }
    }
    private function packMessage($event_id)
    {
       $data_frame_events = config('data-frame.data_frame_events');
        $message = '';
        $json_request = [];
        foreach ($data_frame_events as $event) {
            if ($event['event_id'] == $event_id) {


                foreach ($event['frame'] as $index => $datum) {
                    if ($datum['variable_name'] == 'id_event_log') {
                        //$value = pack($datum['type'], null);
                        //$message = $message . $value;
                        $json_request[$datum['variable_name']] = null;
                    } elseif ($datum['variable_name'] == 'event_id') {
                        $value = pack($datum['type'], $event['event_id']);
                        $message = $message . $value;
                        $json_request[$datum['variable_name']] = $event['event_id'];
                    } elseif ($datum['variable_name'] == 'crc') {
                        $crc = Crc16::XMODEM($message);
                        $value = pack($datum['type'], $crc);
                        $message = $message . $value;
                    } elseif ($datum['variable_name'] == 'salida_id') {
                        $value = pack($datum['type'], 1);
                        $message = $message . $value;
                        $json_request[$datum['variable_name']] = 1;
                    } else {
                        if ($datum['format'] == 'lenght') {
                            $nextIndex = $index + 1;
                            $nextDatum = isset($event['frame'][$nextIndex]) ? $event['frame'][$nextIndex] : null;
                            if ($nextDatum != null) {
                                $related_parameter = $this->json[$nextDatum['parameter_name']];
                                $init_value = strlen($related_parameter);
                            }
                        } elseif ($datum['format'] == 'unix') {
                            // Artisan::call('ntpdate', ['server' => 'pool.ntp.org']);
                            $now = Carbon::now();
                            $init_value = $now->timestamp;
                            $json_request[$datum['variable_name']] = $init_value;

                        } else {
                            $init_value = $this->json[$datum['variable_name']];
                            $json_request[$datum['variable_name']] = $init_value;
                        }
                        $value = pack($datum['type'], $init_value);
                        $message = $message . $value;
                    }
                }
            }
        }
        return $message;
    }

}
