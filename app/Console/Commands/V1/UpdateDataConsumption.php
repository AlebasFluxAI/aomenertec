<?php

namespace App\Console\Commands\V1;


use App\Jobs\V1\Enertec\UnpackDataJob;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use http\Client;
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
        $now =new Carbon();
        $j=0;
        $i = 0;
        $queues = ['spot1', 'spot2', 'spot4', 'spot5'];
        foreach (MicrocontrollerData::select('id', 'source_timestamp')
                     ->whereDate('created_at', $now->format('Y-m-d'))
                     ->whereTime('created_at','>=', $now->subHours(2)->format('H:00:00'))
                     ->whereNull('client_id')
                     ->orderBy('source_timestamp')
                     ->cursor() as $item) {
            echo $i."\n";
            dispatch(new UnpackDataJob($item->id))->onQueue('spot4');
            $i++;
        }

    }
}
