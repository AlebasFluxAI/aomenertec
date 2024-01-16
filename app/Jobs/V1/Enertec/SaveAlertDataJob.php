<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\ClientAlert;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveAlertDataJob implements ShouldQueue
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
    public $raw_json;
    public $source_timestamp;

    public function __construct($raw_json)
    {
        $this->raw_json = $raw_json;
        $this->source_timestamp = new Carbon();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->alertVariableEvent();
    }

    private function alertVariableEvent()
    {
        $flags_frame = config('data-frame.flags_frame');
        $decode = bin2hex(base64_decode($this->raw_json));
        $timestamp = (unpack('l', hex2bin(substr($decode, 64, 8)))[1]);
        $date = new Carbon();
        $date->setTimestamp($timestamp);
        $current_time = Carbon::now();
        if($date->diffInYears($current_time) < 1){
            $flag = $this->calculateValueAlert(5, $decode);
            $binary_flags = sprintf("%064b", ($flag));

            $equipment_serial = str_pad($this->calculateValueAlert(2, $decode), 6, "0", STR_PAD_LEFT);
            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                ->first();
            if ($equipment == null) {
                return;
            }
            $client = $equipment->clients()->first();
            if ($client == null) {
                return;
            }
            $timestamp = $this->calculateValueAlert(6, $decode);

            $this->source_timestamp->setTimestamp($timestamp);
            $value = 0;
            foreach ($flags_frame as $item) {
                if ($item['id'] >= 14 and $item['id'] <= 49) {
                    $alert = $client->clientAlertConfiguration()->where('flag_id', $item['id'])->first();
                    $type = "";
                    $split = substr($binary_flags, $item['bit'], 1);
                    if ($split == "1") {
                        if ($item['flag_name'] == 'flagOpened') {
                            $value = 1;
                            $type = ClientAlert::ALERT;
                        } else {
                            $value = $this->calculateValueAlert($item['variable_id'], $decode);

                            if ($alert) {
                                if ($alert->active_control) {
                                    if ($alert->min_alert != 0) {
                                        if ($value < $alert->min_alert) {
                                            $type = ClientAlert::ALERT;
                                        }
                                    }
                                    if ($alert->max_alert != 0) {
                                        if ($value > $alert->max_alert) {
                                            $type = ClientAlert::ALERT;
                                        }
                                    }
                                    if ($alert->min_control != 0) {
                                        if ($value < $alert->min_control) {
                                            $type = ClientAlert::CONTROL;
                                        }
                                    }
                                    if ($alert->max_control != 0) {
                                        if ($value > $alert->max_control) {
                                            $type = ClientAlert::CONTROL;
                                        }
                                    }
                                } else {
                                    if ($alert->min_alert != 0) {
                                        if ($value < $alert->min_alert) {
                                            $type = ClientAlert::ALERT;
                                        }
                                    }
                                    if ($alert->max_alert != 0) {
                                        if ($value > $alert->max_alert) {
                                            $type = ClientAlert::ALERT;
                                        }
                                    }
                                }
                            }
                        }
                        if ($alert) {
                            if ($type != "") {
                                $microcontroller_data = MicrocontrollerData::whereRawJson($this->raw_json)->first();
                                ClientAlert::create([
                                    'client_id' => $client->id,
                                    'microcontroller_data_id' => ($microcontroller_data) ? $microcontroller_data->id : null,
                                    'client_alert_configuration_id' => $alert->id,
                                    'value' => $value,
                                    'type' => $type,
                                    'source_timestamp' => $this->source_timestamp->format('Y-m-d H:i:s')
                                ]);
                            }
                        }
                    }
                }
            }
        }

    }

    private function calculateValueAlert($variable_id, $decode)
    {
        $data_frame = collect(config('data-frame.data_frame'));
        $variable = $data_frame->where('id', $variable_id)->first();
        $split = substr($decode, ($variable['start']), ($variable['lenght']));
        $bin = hex2bin($split);
        if ($variable['start'] >= 464) {
            $value = (unpack($variable['type'], $bin)[1]) / 1000;
        } else {
            if ($variable['variable_name'] == "flags") {
                $value = strval(unpack($variable['type'], $bin)[1]);
            } else {
                $value = unpack($variable['type'], $bin)[1];
            }
        }
        return $value;
    }
}
