<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\BackupMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\MicrocontrollerData;
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
    protected $description = 'This command will run every three minutes recording data consumption to clients';

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
            ->whereNull('source_timestamp')
            ->orderBy('id')->get();
        foreach ($data as $item){
            BackupMicrocontrollerDataJob::dispatch($item)->onQueue('update_data');
        }
        $data_aux = MicrocontrollerData::whereNull('client_id')
            ->whereNotNull('source_timestamp')
            ->orderBy('source_timestamp')
            ->get();
        foreach ($data_aux as $item){
            UpdatedMicrocontrollerDataJob::dispatch($item)->onQueue('update_data');
        }

    }
}
