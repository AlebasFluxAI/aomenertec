<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\UpdateTimestampDataJob;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        $data = MicrocontrollerData::select('id', 'source_timestamp', 'raw_json', 'status')->whereNull('source_timestamp')
            ->whereNull('client_id')->orderBy('id', 'asc')->get();

        if ($data) {
            foreach ($data as $item) {
                //echo $item->id."\n";
                if ($item->status == MicrocontrollerData::PENDING_TIMESTAMP or $item->status == null) {
                  //  echo "ok= ".$item->id."\n";
                    dispatch(new UpdateTimestampDataJob($item))->onQueue('spot4');
                    $item->status = MicrocontrollerData::PROCESING_TIMESTAMP;
                    $item->saveQuietly();
                }
            }
        }
    }
}
