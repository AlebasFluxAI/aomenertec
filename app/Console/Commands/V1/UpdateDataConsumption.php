<?php

namespace App\Console\Commands\V1;


use App\Jobs\V1\Enertec\UnpackDataJob;
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
        foreach (MicrocontrollerData::select('id', 'source_timestamp', 'raw_json', 'status')
                     // ->where('created_at', '>=', $now->subDay()->format('Y-m-d H:00:00'))
                     ->whereNull('client_id')
                     ->whereNotNull('source_timestamp')
                     ->orderBy('source_timestamp')
                     ->cursor() as $item) {
            // echo $item->id;
            if($item->status == MicrocontrollerData::SUCCESS_TIMESTAMP or $item->status == MicrocontrollerData::PROCESING_TIMESTAMP or $item->status == MicrocontrollerData::SUCCESS_UNPACK) {
                dispatch(new UnpackDataJob($item->id))->onQueue('spot4');
                $item->status = MicrocontrollerData::SUCCESS_UNPACK;
                $item->saveQuietly();
            }
        }

    }
}
