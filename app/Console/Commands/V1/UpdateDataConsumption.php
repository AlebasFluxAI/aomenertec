<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\AuxData;
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
            foreach ($data as $item) {
                $decode = bin2hex(base64_decode($item->raw_json));
                foreach ($data_frame as $data) {
                    try {
                        $split = substr($decode, ($data['start']), ($data['lenght']));
                        $bin = hex2bin($split);
                        if ($data['start'] >= 440) {
                            $json[$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                            $json["data_".$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                        } else {
                            if ($data['variable_name'] == "flags") {
                                $json[$data['variable_name']] = strval(unpack($data['type'], $bin)[1]);
                            } else {
                                $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
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
                if ($json['import_wh'] == 0) {
                    $item->updateQuietly();
                    $item->delete();
                    return;
                }
                $item->update();
            }
        }
    }
}
