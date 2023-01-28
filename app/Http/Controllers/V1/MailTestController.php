<?php

namespace App\Http\Controllers\V1;

use App\Events\RealTimeMonitoringEvent;
use App\Events\UserNotificationEvent;
use App\Http\Resources\V1\NotificationTypes;
use App\Jobs\V1\Enertec\JsonEdit;
use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataJob;
use App\Mail\User\UserCratedMail;
use App\Mail\User\UserResetPasswordMail;
use App\Mail\WorkOrder\WorkOrderUpdatedMail;
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
    public $day_ref;
    public function userCreatedNotification()
    {
        $this->day_ref = new Carbon('2022-12-31');
        $billing_day = $this->day_ref->format('d');
        if ($billing_day == $this->day_ref->format('t')){
            $billing_day_clients = ClientConfiguration::whereBillingDay(31)->get()->pluck('client_id');
        } else{
            $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->orderBy('client_id')->get()->pluck('client_id');
        }
        $clients_aux = Client::find($billing_day_clients);

        $clients = $clients_aux->where('has_telemetry', true)->all();
        if (count($clients)>0) {
            if ($this->day_ref->format('m') == '01') {
                $month_aux = 12;
                $year_aux = $this->day_ref->format('Y') - 1;
            } else {
                $month_aux = $this->day_ref->format('m') - 1;
                if ($month_aux<10) {
                    $month_aux = '0'.$month_aux;
                }
                $year_aux = $this->day_ref->format('Y');
            }
            if ($billing_day == $this->day_ref->format('t')){
                $date_aux = Carbon::create($year_aux, $month_aux, 2);
                $start_date = Carbon::create($year_aux, $month_aux, $date_aux->format('t'), 23, 59, 59);
                $end_date = Carbon::create($this->day_ref->format('Y'), $this->day_ref->format('m'), $this->day_ref->format('t'), 23, 59, 59);
            } else{
                $start_date = Carbon::create($year_aux, $month_aux, ($billing_day), 23, 59, 59);
                $end_date = Carbon::create($this->day_ref->format('Y'), $this->day_ref->format('m'), $billing_day, "23", "59", 59);
            }
            foreach ($clients as $client_aux) {
                $client = Client::find($client_aux->id);
                if ($billing_day == $this->day_ref->format('t')){

                    $data_month = $client->dailyMicrocontrollerData()
                        ->where('year', $this->day_ref->format('Y'))
                        ->where('month', $this->day_ref->format('m'))
                        ->whereBetween('day', ['01', $billing_day])
                        ->get();
                } else{
                    $data_aux = $client->dailyMicrocontrollerData()
                        ->where('year', $year_aux)
                        ->where('month', ($month_aux))
                        ->whereBetween('day', [str_pad((strval(($billing_day+1))), 2, "0", STR_PAD_LEFT), ($start_date->format('t'))]);
                    $data_month = $client->dailyMicrocontrollerData()
                        ->where('year', $this->day_ref->format('Y'))
                        ->where('month', $this->day_ref->format('m'))
                        ->whereBetween('day', ['01', $billing_day])
                        ->union($data_aux)
                        ->get();
                }
                if (count($data_month) > 0) {
                    $end_data = $client->microcontrollerData()
                        ->whereBetween('source_timestamp', [$start_date->format('Y-m-d H:i:s'), $end_date->format('Y-m-d 23:59:59')])
                        ->orderBy('source_timestamp', 'desc')
                        ->first();

                    $start_data = $client->microcontrollerData()
                        ->whereDate('source_timestamp', $start_date->format('Y-m-d 00:00:00'))
                        ->orderBy('source_timestamp', 'desc')
                        ->first();
                    if (empty($start_data)){
                        $start_data = $client->microcontrollerData()
                            ->whereDate('source_timestamp', '<', $start_date->format('Y-m-d 00:00:00'))
                            ->orderBy('source_timestamp', 'desc')
                            ->first();
                        if (empty($start_data)){
                            $start_data = $client->microcontrollerData()
                                ->whereBetween('source_timestamp', [$start_date->format('Y-m-d H:i:s'), $end_date->format('Y-m-d 23:59:59')])
                                ->orderBy('source_timestamp')
                                ->first();
                        }
                    }
                    if ($end_data) {
                        $reference_data = $end_data;
                        $json = json_decode($reference_data->raw_json, true);
                        $penalizable_inductive_month = 0;
                        $penalizable_capacitive_month = 0;
                        $interval_active_month = $end_data->accumulated_real_consumption - $start_data->accumulated_real_consumption;
                        $interval_capacitive_month = $end_data->accumulated_reactive_capacitive_consumption - $start_data->accumulated_reactive_capacitive_consumption;
                        $interval_inductive_month = $end_data->accumulated_reactive_inductive_consumption - $start_data->accumulated_reactive_inductive_consumption;
                        foreach ($data_month as $item) {
                            $penalizable_inductive_month = $penalizable_inductive_month + $item->penalizable_reactive_inductive_consumption;
                            $penalizable_capacitive_month = $penalizable_capacitive_month + $item->penalizable_reactive_capacitive_consumption;
                        }
                        $json_first = json_decode($start_data->raw_json, true);
                        $json['kwh_interval'] = $json['import_wh'] - $json_first['import_wh'];
                        $json['ph1_kwh_interval'] = $json['ph1_import_kwh'] - $json_first['ph1_import_kwh'];
                        $json['ph2_kwh_interval'] = $json['ph2_import_kwh'] - $json_first['ph2_import_kwh'];
                        $json['ph3_kwh_interval'] = $json['ph3_import_kwh'] - $json_first['ph3_import_kwh'];
                        $json['varh_interval'] = $json['import_VArh'] - $json_first['import_VArh'];
                        $json['ph1_varh_interval'] = $json['ph1_import_kvarh'] - $json_first['ph1_import_kvarh'];
                        $json['ph2_varh_interval'] = $json['ph2_import_kvarh'] - $json_first['ph2_import_kvarh'];
                        $json['ph3_varh_interval'] = $json['ph3_import_kvarh'] - $json_first['ph3_import_kvarh'];
                        $json['varCh_interval'] = $json['varCh_acumm'] - $json_first['varCh_acumm'];
                        $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - $json_first['ph1_varCh_acumm'];
                        $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - $json_first['ph2_varCh_acumm'];
                        $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - $json_first['ph3_varCh_acumm'];
                        $json['varLh_interval'] = $json['varLh_acumm'] - $json_first['varLh_acumm'];
                        $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - $json_first['ph1_varLh_acumm'];
                        $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - $json_first['ph2_varLh_acumm'];
                        $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - $json_first['ph3_varLh_acumm'];

                        MonthlyMicrocontrollerData::updateOrCreate([
                            'year' => $this->day_ref->format('Y'),
                            'month' => $this->day_ref->format('m'),
                            'day' => $billing_day,
                            'client_id' => $client->id],
                            ['microcontroller_data_id' => $reference_data->id,
                                'interval_real_consumption' => $interval_active_month,
                                'interval_reactive_capacitive_consumption' => $interval_capacitive_month,
                                'interval_reactive_inductive_consumption' => $interval_inductive_month,
                                'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_month,
                                'penalizable_reactive_inductive_consumption' => $penalizable_inductive_month,
                                'raw_json' => json_encode($json),
                            ]);
                    }
                }
            }
        }

       // return (new WorkOrderUpdatedMail(WorkOrder::find(29)))->render();
    }

    public function whatsappNotification()
    {
        $this->alertVariableEvent();


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
    }
    private function alertVariableEvent()
    {
        $raw_json = '3dc5AgAAAAChuw0AAAAAAAAAiEEAAJxBASAAwHh9718ID6ZjEEADQ2AqA0OQjgNDpMreQihu2EK49LhCQ5tjRgDUWEbPRTxGtMdjRpBCXUbKFD1GXfANRD8+MEWfH4xESM5\/PzzEej\/S5X4\/6Gl+P+By6D9gZz9BmIixQLAmzkCrtR1HE+WMRQD2b0JrV45Ia2ZCQO7Vp0fHGddFoGhjQxCpY0Pw62JDexReQHsUXkBSuF5AKVz\/QHE98kA9Ch9BAAAAAAAAAAAAAAAAcoTER2c5v0fSn7VHSaDRRsg6AEffQc1GAAAAAOjeJUEAAAAAa6FQQgAAAAA33KhB';
        //$microcontroller_data = MicrocontrollerData::find(1615799);
        $microcontroller_data = null;
        $flags_frame = config('data-frame.flags_frame');
        $decode = bin2hex(base64_decode($raw_json));
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
        $value = 0;
        dd($binary_flags);
        foreach ($flags_frame as $item) {
            if ($item['id'] >= 14 and $item['id'] <= 49) {
                $type = "";
                $alert = null;
                $split = substr($binary_flags, $item['bit'], 1);
                if ($split == "1") {
                    if ($item['flag_name'] == 'flagOpened') {
                        $value = 1;
                        $type = ClientAlert::ALERT;
                    } else {
                        dd($item['flag_name']);
                        $alert = $client->clientAlertConfiguration()->where('flag_id', $item['id'])->first();
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
                            ClientAlert::create([
                                'client_id' => $client->id,
                                'microcontroller_data_id' => ($microcontroller_data) ? $microcontroller_data->id : null,
                                'client_alert_configuration_id' => $alert->id,
                                'value' => $value,
                                'type' => $type
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
