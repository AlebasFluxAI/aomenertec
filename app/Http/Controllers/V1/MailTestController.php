<?php

namespace App\Http\Controllers\V1;

use App\Events\setProgressOtaUploadEvent;
use App\Jobs\V1\Enertec\AlertNotificationJob;
use App\Jobs\V1\Enertec\SaveAlertDataJob;
use App\Models\V1\Api\EventLog;
use App\Models\V1\AuxData;
use App\Models\V1\ClientAlert;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use App\ModulesAux\MQTT;
use Carbon\Carbon;
use Crc16\Crc16;
use Illuminate\Support\Facades\Request;


class MailTestController
{
    public $raw_json;
    public $source_timestamp;

    public function imageTest()
    {
        return view('partials.test_image');
    }

    public function userCreatedNotification()
    {
        dd('ok1');
        $data = AuxData::where('created_at', '>', '2023-08-01')->where('data', 'LIKE', '%UsWo9wEAAAAWA%')->get();

        foreach ($data as $item) {
            MicrocontrollerData::create([
                'raw_json' => $item->data
            ]);
        }
    }

    public function eventTest(){
        event(new setProgressOtaUploadEvent(30, 15));


    }

    public function whatsappNotification()
    {
        $item = '9fReBQAAAAChuw0AAAAAAAAAAAAAAAAACAAAAAAAAAC/fg5noNL/QigdAEMwSgBDjP6wQvx7yUKIF71CB3gpRi+1QUaj9jVGQ0swRkNWSEbCDj1GXlFCRUlyTEWlOU1FzhZ2P2SHdz/QZHY/VLZ2P1RyfEFAwW5BSGJ6QWABeEG2QAhHPw4XRgAScEIE/CZJa2ZCQLUmXUi3yxlG8BZeQ5BdXkP4Jl1DAABQQD0KR0CamUlAXI/iQDMzu0AzM9NAAAAAAAAAAAAAAAAA795hSB5QYEj/wFlIAcOPR7D2nUe5k4xH2ZwcRmB2JUZkQBxGSH68Q7wN8kJbEW9DAAAAAA==';
        $data_frame = config('data-frame.data_frame');
        $date = Carbon::now();
        $raw_json = json_decode($item, true);
        $last_data = null;
        $client = null;
        if ($raw_json === null) {
            $decode = bin2hex(base64_decode($item));
            $split = substr($decode, (16), (16));
            $bin = hex2bin($split);
            $equipment_serial = str_pad(unpack('Q', $bin)[1], 6, "0", STR_PAD_LEFT);
            $equipment_type = EquipmentType::whereType('GABINETE')->first();
            $equipment = $equipment_type->equipment()->whereSerial($equipment_serial)
                ->first();
            if($equipment == null){
                $equipment_type = EquipmentType::whereType('MEDIDOR ELECTRICO')->first();
                $equipment = $equipment_type->equipment()->whereSerial($equipment_serial)->first();
            }
            if ($equipment) {
                $client = $equipment->clients()->first();
                if ($client) {
                    if ($client->stopUnpackClient()->exists()) {
                        return;
                    }
                    $last_data = $client->microcontrollerData()->orderBy('source_timestamp', 'desc')->first();
                }
            }

            if (strlen($item) > 20) {
                if ($last_data) {
                    $last_raw_json = json_decode($last_data->raw_json, true);
                }
                $source_timestamp = Carbon::now();
                if ($date->diffInDays($source_timestamp) <= 365) {
                    foreach ($data_frame as $data) {
                        try {
                            $split = substr($decode, ($data['start']), ($data['lenght']));

                            $bin = hex2bin($split);
                            if (strlen($bin) == ($data['lenght'] / 2)) {

                                    if ($data['variable_name'] == "flags") {
                                        $json[$data['variable_name']] = strval(unpack($data['type'], $bin)[1]);
                                        $json_aux[$data['variable_name']] = [strval(unpack($data['type'], $bin)[1]), $split, $bin];

                                    } else {

                                        if ($data['variable_name'] == "equipment_id") {
                                            $json[$data['variable_name']] = $equipment_serial;
                                            $json_aux[$data['variable_name']] = [$equipment_serial, $split, $bin];

                                        } else {
                                            $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                                            $json_aux[$data['variable_name']] = [unpack($data['type'], $bin)[1], $split, $bin];
                                        }
                                    }

                                if ($data['variable_name'] == 'timestamp') {
                                    $date_aux = new Carbon();
                                    $timestamp_unix = $json[$data['variable_name']];
                                    $date_aux->setTimestamp($timestamp_unix);
                                }
//
//                                if ($data['start'] >= 72) {
//                                    if ($json[$data['variable_name']] < $data['min'] or $json[$data['variable_name']] > $data['max']) {
//                                        if (!$data['default']) {
//                                            $json[$data['variable_name']] = $data['default'];
//                                        } else {
//                                            if ($last_data) {
//                                                if ($data['start'] >= 450) {
//                                                    $json[$data['variable_name']] = $last_raw_json[$data["data_" .'variable_name']];
//                                                } else {
//                                                    $json[$data['variable_name']] = $last_raw_json[$data['variable_name']];
//                                                }
//                                            } else {
//                                                $json[$data['variable_name']] = 0;
//                                            }
//                                        }
//                                    }
//                                }

                                if (is_nan($json[$data['variable_name']])) {
                                    $json[$data['variable_name']] = null;
                                }

                                if ($data['variable_name'] == "volt_dc") {
                                    break;
                                }
                            } else {
                                if ($data['start'] >= 72) {
                                    if (!$data['default']) {
                                        $json[$data['variable_name']] = $data['default'];
                                    } else {
                                        if ($last_data) {
                                            if (isset($last_raw_json[$data['variable_name']])) {
                                                $json[$data['variable_name']] = $last_raw_json[$data['variable_name']];
                                            } else {
                                                $json[$data['variable_name']] = 0;
                                            }
                                        } else {
                                            $json[$data['variable_name']] = 0;
                                        }
                                    }
                                }
                                if ($data['variable_name'] == "volt_dc") {
                                    break;
                                }
                            }
                        } catch (Exception $e) {
                            echo 'Excepción capturada: ', $e->getMessage(), "\n";
                        }
                    }
                    $item = $json;
                    $json['date'] = $date_aux->format('Y-m-d H:i:s');
                    //$json['client_id'] = $client->id;
                    dd($json_aux, $decode);

//                    if ($json['import_wh'] <= 0) {
//                        if ($last_data) {
//                            if ($last_raw_json['import_wh']>0) {
//                                $item->updateQuietly();
//                                $item->forceDelete();
//                                return;
//                            }
//                        }
//                    }

                    if ($client) {
                        //if (!$client->stopUnpackClient()->exists()) {

                        $item->save();
                        //dispatch(new JsonEdit($item->id, true))->onQueue($this->queue);
                        //}
                    } else {
                        $item->forceDelete();
                    }
                } else {
                    $item->forceDelete();
                }
            } else {
                $item->forceDelete();
            }
        }

    }
    //$this->alertVariableEvent();



    //}
    private function alertVariableEvent()
    {
        $flags_frame = config('data-frame.flags_frame');
        $decode = bin2hex(base64_decode($this->raw_json));

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
