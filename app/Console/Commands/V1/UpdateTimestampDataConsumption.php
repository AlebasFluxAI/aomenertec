<?php

namespace App\Console\Commands\V1;

use Illuminate\Console\Command;
use App\Models\V1\AuxData;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;

class UpdateTimestampDataConsumption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:update_timestamp_data_consumption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run every minute update timestamp data consumption to clients';

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
        $data = MicrocontrollerData::select('source_timestamp', 'raw_json')->whereNull('source_timestamp')
            ->whereNull('client_id')->get();
        if ($data) {
            foreach ($data as $item) {
                if (json_decode($item->raw_json, true) == null) {
                    $decode = bin2hex(base64_decode($item->raw_json));
                    $timestamp = (unpack('l', hex2bin(substr($decode, 64, 8)))[1]);
                    $date = new Carbon();
                    $date->setTimestamp($timestamp);
                    $item->source_timestamp = $date->format("Y-m-d H:i:s");
                    $item->saveQuietly();
                }
            }
        }
    }
}
