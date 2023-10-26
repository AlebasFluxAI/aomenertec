<?php

namespace App\Http\Controllers\V1;

use App\Events\RealTimeMonitoringEvent;
use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use App\Jobs\V1\Enertec\JsonEdit;
use App\Jobs\V1\Enertec\SaveAlertDataJob;
use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataMonthJob;
use App\Mail\User\UserCratedMail;
use App\Mail\User\UserResetPasswordMail;
use App\Mail\WorkOrder\WorkOrderUpdatedMail;
use App\Models\V1\AuxData;
use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\ClientDigitalOutputAlertConfiguration;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\MonthlyMicrocontrollerData;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use App\Notifications\Alert\AlertControlNotification;
use App\Notifications\Alert\AlertNotification;
use App\Notifications\User\UserCreatedNotification;
use App\Notifications\User\UserResetPasswordNotification;
use Carbon\Carbon;
use Crc16\Crc16;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;

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
        $data = AuxData::where('created_at', '>', '2023-08-01')->where('data', 'LIKE', '%UsWo9wEAAAAWA%')->get();

        foreach ($data as $item){
            MicrocontrollerData::create([
                'raw_json'=>$item->data
            ]);
        }
    }

    public function whatsappNotification()
    {
        $item = 'TmG8AAAAAAA6kA0AAAAAAAAAiEEAAJxBAAAAABAAAKB7mTlldY/lQgAAAAAAAAAALSyLPwAAAAAAAAAA8/zmQgAAAAAAAAAAUlbnQgAAAAAAAAAAAAAAAAAAAAAAAAAAq9t/PwAAAAAAAAAAAAAAAGOG7z8AAAAAAAAAAAAAAAAAAAAAAAAAAArQb0IvvQlDppvEO6rxm0GF64E/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMbXsjwAAAAAAAAAAAAAAAAAAAAA';
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

            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                ->first();
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
                $source_timestamp = Carbon::create('2023-07-17 16:55:43.000');
                if ($date->diffInDays($source_timestamp) <= 365) {
                    foreach ($data_frame as $data) {
                        try {
                            $split = substr($decode, ($data['start']), ($data['lenght']));

                            $bin = hex2bin($split);
                            if (strlen($bin) == ($data['lenght'] / 2)) {
                                if ($data['start'] >= 450) {
                                    $json[$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                                    $json["data_" . $data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                                } else {
                                    if ($data['variable_name'] == "flags") {
                                        $json[$data['variable_name']] = strval(unpack($data['type'], $bin)[1]);
                                    } else {
                                        if ($data['variable_name'] == "equipment_id") {
                                            $json[$data['variable_name']] = $equipment_serial;
                                        } else {
                                            $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                                        }
                                    }
                                }
                                if($data['variable_name'] == 'timestamp'){
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

                                if ($data['variable_name'] == "ph3_varLh_acumm") {
                                    break;
                                }
                            } else {
//                                if ($data['start'] >= 72) {
//                                    if (!$data['default']) {
//                                        $json[$data['variable_name']] = $data['default'];
//                                    } else {
//                                        if ($last_data) {
//                                            if (isset($last_raw_json[$data['variable_name']])) {
//                                                $json[$data['variable_name']] = $last_raw_json[$data['variable_name']];
//                                            } else {
//                                                $json[$data['variable_name']] = 0;
//                                            }
//                                        } else {
//                                            $json[$data['variable_name']] = 0;
//                                        }
//                                    }
//                                }
                            }
                        } catch (Exception $e) {
                            echo 'Excepción capturada: ', $e->getMessage(), "\n";
                        }
                    }
                    $item = $json;
                    $json['date'] = $date_aux->format('Y-m-d H:i:s');
                    $json['client_id'] = $client->id;
                    dd($json);

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
                    } else{
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


        /*$frame = [10=> ['ssid', 'password', 'variable']];
        $raw_json = 'Fc1bBwAAAACguw0AAAAAAAAAiEEAAJxBAEgAAAAAAFAijI9j5WH1Qp9r90K/MPVCJZKOPgFuDD51y6E+++1rQX86JEGhp/NBREgJQvElh0FWQxtCuEL4waoLVcHAoMHBAY7YPpzXIT+eWEk/FUghPwVXgsKZW03C/PAawloTTcLGfl5CxK2JwpfYb0Jyg5VFAAAAALDCHURB6L9EBW9VQ59TVUMqbVRDAAAAAAAAAAAAAAAAv7riRHOOSURJHE1FAAAAAAAAAAAAAAAAINgBRX5Or0QKD6NE5TCVQ5WjKkNgBSJDYY+4PQAAAAC+MR49AAAAABr5kD0AAAAA';
        $ssid = "holaaaaaaaaaaaxxxx";
        $pass = "123456789015";
        $float = pack('f', 110.26);
        $event_id = pack('C', 10);
        $q = pack('C', ord('Q'));
        $l = pack('C', ord('l'));
        $f = pack('C', ord('f'));
        $message = $event_id.pack('C', strlen($ssid)).$ssid.pack('C', strlen($pass)).$pass.$f.$float;
        $crc = Crc16::XMODEM($message);
        $crc_pack = pack('f', $crc);
        $message = $message.$crc_pack;
        ///////////////////////////////////////////////////
        $crc_unpack = unpack('f', substr($message,-4))[1];
        $data_crc = substr($message, 0, -4);
        $event = unpack('C', $message[0])[1];
        if (Crc16::XMODEM($data_crc) == $crc_unpack){
            $i=1;
            $j=0;
            $json = [];
            $event_keys = $frame[$event];
            while (true){
                $format = $data_crc[$i];
                $i++;
                if ($format == 'f'){
                    $size = 4;
                    $datum = unpack('f', substr($data_crc, $i, 4))[1];
                } elseif ($format == 'l'){
                    $size = 8;
                    $datum = unpack('l', substr($data_crc, $i, 8))[1];
                } elseif ($format == 'Q'){
                    $size = 16;
                    $datum = unpack('Q', substr($data_crc, $i, 16))[1];
                } else{
                    $size = unpack('C', $format)[1];
                    $datum = substr($data_crc, $i, $size);
                }
                $i = $i + $size;
                $json[$event_keys[$j]] = $datum;
                $j++;
                if ($i >= strlen($data_crc)){
                    break;
                }
            }
            $json['msj_init']= $message;
            dd($json);

        }*/
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
                        if($alert) {
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
