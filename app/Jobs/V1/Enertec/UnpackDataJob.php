<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UnpackDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $item;

    public function __construct($item)
    {
        $this->item = MicrocontrollerData::find($item);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $data_frame = config('data-frame.data_frame');
        $date = Carbon::now();
        $raw_json = json_decode($this->item->raw_json, true);
        $last_data = null;
        $client = null;
        if ($raw_json === null) {
            $decode = bin2hex(base64_decode($this->item->raw_json));
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

            if (strlen($this->item->raw_json) > 20) {
                if ($last_data) {
                    $last_raw_json = json_decode($last_data->raw_json, true);
                }
                $source_timestamp = Carbon::create($this->item->source_timestamp);
                if ($date->diffInDays($source_timestamp) <= 365) {
                    foreach ($data_frame as $data) {
                        try {
                            $split = substr($decode, ($data['start']), ($data['lenght']));
                            $bin = hex2bin($split);
                            if (strlen($bin) == ($data['lenght'] / 2)) {
                                if ($data['start'] >= 450) {
                                    if ($data['variable_name'] == 'volt_dc'){
                                        $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                                    } else{
                                        $json[$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                                        $json["data_" . $data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                                    }
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
                                if ($data['start'] >= 72) {
                                    if ($json[$data['variable_name']] <= $data['min'] or $json[$data['variable_name']] > $data['max']) {
                                        if (!$data['default']) {
                                            $json[$data['variable_name']] = $data['default'];
                                        } else {
                                            if ($last_data) {
                                                if ($data['start'] >= 450) {
                                                    if ($data['variable_name'] != 'volt_dc') {
                                                        $json[$data['variable_name']] = $last_raw_json[$data["data_" . 'variable_name']];
                                                    } else{
                                                        $json[$data['variable_name']] = $last_raw_json[$data['variable_name']];
                                                    }
                                                } else {
                                                    $json[$data['variable_name']] = $last_raw_json[$data['variable_name']];
                                                }
                                            } else {
                                                $json[$data['variable_name']] = 0;
                                            }
                                        }
                                    }
                                }

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
                    $this->item->raw_json = $json;

                    if ($json['import_wh'] <= 0) {
                        if ($last_data) {
                            if ($last_raw_json['import_wh'] > 0) {
                                $this->item->updateQuietly();
                                $this->item->forceDelete();
                                return;
                            }
                        }
                    }

                    if ($client) {
                        //if (!$client->stopUnpackClient()->exists()) {

                        $this->item->save();
                        //dispatch(new JsonEdit($this->item->id, true))->onQueue($this->queue);
                        //}
                    } else {
                        $this->item->forceDelete();
                    }
                } else {
                    $this->item->forceDelete();
                }
            } else {
                $this->item->forceDelete();
            }
        } else {
            $raw_json['ph1_varCh_acumm'] = $raw_json['data_ph1_varCh_acumm'] ?? 0;
            $raw_json['ph2_varCh_acumm'] = $raw_json['data_ph2_varCh_acumm'] ?? 0;
            $raw_json['ph3_varCh_acumm'] = $raw_json['data_ph3_varCh_acumm'] ?? 0;
            $raw_json['ph1_varLh_acumm'] = $raw_json['data_ph1_varLh_acumm'] ?? 0;
            $raw_json['ph2_varLh_acumm'] = $raw_json['data_ph2_varLh_acumm'] ?? 0;
            $raw_json['ph3_varLh_acumm'] = $raw_json['data_ph3_varLh_acumm'] ?? 0;
            if ($this->item->manually) {
                $this->item->interval_real_consumption = 1;
            }
            $this->item->raw_json = json_encode($raw_json);
            $this->item->save();
            /*$equipment_serial = str_pad($raw_json['equipment_id'], 6, "0", STR_PAD_LEFT);
            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)->first();
            if ($equipment) {
                $client = $equipment->clients()->first();
                if ($client) {
                    if (!$client->stopUnpackClient()->exists()) {
                        $this->item->save();
                        dispatch(new JsonEdit($this->item, true))->onQueue($this->queue);
                    }
                }
            }*/
        }
    }
}
