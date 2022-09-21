<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\AuxData;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateDataConsumption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:update_data_consumption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run every five minutes recording data consumption to clients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = MicrocontrollerData::whereNull('client_id')
            ->whereNotNull('source_timestamp')
            ->orderBy('source_timestamp')
            ->get();
        if ($data) {
            $data_frame = config('data-frame.data_frame');
            $date = Carbon::now();
            foreach ($data as $item) {
                $last_data = null;
                $decode = bin2hex(base64_decode($item->raw_json));
                $equipment_serial = $this->calculateValueAlert(2, $decode);
                $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                    ->first();
                if ($equipment) {
                    $client = $equipment->clients()->first();
                    if ($client) {
                        $last_data = $client->microcontrollerData()->orderBy('source_timestamp', 'desc')->first();
                    }
                }
                if ($last_data) {
                    $last_raw_json = json_decode($last_data->raw_json, true);
                }
                $source_timestamp = Carbon::create($item->source_timestamp);
                if ($date->diffInDays($source_timestamp) <= 30) {
                    $pos = strpos(strval($item->raw_json), '{');
                    if ($pos === false) {
                        foreach ($data_frame as $data) {
                            try {
                                $split = substr($decode, ($data['start']), ($data['lenght']));
                                $bin = hex2bin($split);
                                if ($data['start'] >= 440) {
                                    $json[$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                                    $json["data_" . $data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                                } else {
                                    if ($data['variable_name'] == "flags") {
                                        $json[$data['variable_name']] = strval(unpack($data['type'], $bin)[1]);
                                    } else {
                                        $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                                    }
                                }
                                if ($data['start'] >= 72) {
                                    if ($json[$data['variable_name']] < $data['min'] or $json[$data['variable_name']] > $data['max']) {
                                        if ($data['default'] = true) {
                                            if ($last_data) {
                                                $json[$data['variable_name']] = $last_raw_json[$data['variable_name']];
                                            } else{
                                                $json[$data['variable_name']] = 0;
                                            }
                                        } else {
                                            $json[$data['variable_name']] = $data['default'];
                                        }
                                    }
                                }

                                if (is_nan($json[$data['variable_name']])) {
                                    $json[$data['variable_name']] = null;
                                }

                                if ($data['variable_name'] == "ph3_varLh_acumm") {
                                    break;
                                }
                            } catch (Exception $e) {
                                echo 'Excepción capturada: ', $e->getMessage(), "\n";
                            }
                        }
                        $item->raw_json = $json;
                        if ($json['import_wh'] <= 0) {
                            $item->updateQuietly();
                            $item->delete();
                            return;
                        }
                    } else {
                        $source_timestamp->addMinute();
                        $item->source_timestamp = $source_timestamp->format("Y-m-d H:i:s");
                    }
                    $item->save();
                } else{
                    $item->delete();
                }

            }
        }
    }

    private function calculateValueAlert($variable_id, $decode){
        $data_frame = collect(config('data-frame.data_frame'));
        $variable = $data_frame->where('id', $variable_id)->first();
        $split = substr($decode, ($variable['start']), ($variable['lenght']));
        $bin = hex2bin($split);
        if ($variable['start'] >= 440) {
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
