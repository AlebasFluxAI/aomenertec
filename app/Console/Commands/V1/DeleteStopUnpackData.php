<?php

namespace App\Console\Commands\V1;

use App\Models\V1\StopUnpackDataClient;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteStopUnpackData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:delete_stop_unpack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $stop_clients = StopUnpackDataClient::get();
        $now = new Carbon();
        foreach ($stop_clients as $item) {
            $create_date = Carbon::create($item->created_at);
            if ($now->diffInHours($create_date) >= 1) {
                $item->forceDelete();
            }
        }
    }
}
