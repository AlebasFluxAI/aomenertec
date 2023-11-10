<?php

namespace App\Jobs\V1\Enertec;

use App\Events\RealTimeMonitoringEvent;
use App\Models\V1\EquipmentType;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
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
        if ($data) {
            event(new RealTimeMonitoringEvent($data));
        }
    }

    private function unpackData()
    {
        $data_frame = config('data-frame.data_frame');
        $decode = bin2hex(base64_decode($this->raw_json));
        $split = substr($decode, (16), (16));
        $bin = hex2bin($split);
        $equipment_serial = str_pad(unpack('Q', $bin)[1], 6, "0", STR_PAD_LEFT);
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
            ->first();
        foreach ($data_frame as $data) {
            try {
                $split = substr($decode, ($data['start']), ($data['lenght']));
                if (!$split) {
                    $json[$data['variable_name']] = 0;
                } else {
                    $bin = hex2bin($split);
                    if ($data['start'] >= 450) {
                        $json[$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                    } else {
                        if ($data['variable_name'] == "flags") {
                            $json[$data['variable_name']] = 0;
                        } else {
                            if ($data['variable_name'] == "equipment_id") {
                                $json[$data['variable_name']] = $equipment_serial;
                            } else {
                                $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                            }
                        }
                    }
                }


                if (is_nan($json[$data['variable_name']])) {
                    $json[$data['variable_name']] = null;

                }
                if ($data['variable_name'] == "ph3_varLh_acumm") {
                    break;
                }
                if ($data['start'] >= 496) {
                    break;
                }
            } catch (Exception $e) {
                echo 'Excepción capturada: ', $e->getMessage(), "\n";
            }
        }

        if ($equipment) {
            $client = $equipment->clients()->first();

            $current_time = (new Carbon('now', $client->time_zone))->format('Y-m-d H:i:s');
            $client_id = $client->id;
            $json['timestamp'] = $current_time;
            $json['client_id'] = $client_id;
            return $json;
        }
        return false;
    }
}
