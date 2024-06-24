<?php

namespace App\Console\Commands\V1\OrderData;

use App\Jobs\V1\OrderData\AverageDailyConsumptionJob;
use App\Models\V1\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AverageDaylyConsumptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:order_data:average_daily_consumption_command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'El comando se ejecuta cada dia a la 1:00. Se encarga de actualizar el consumo diario y de promediar los dias de no registro de datos';


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
        $clients = Client::whereHasTelemetry(true)->get();
        $day_reference = Carbon::now()->subDay();
        foreach ($clients as $client) {
            dispatch(new AverageDailyConsumptionJob($client->id, $day_reference))->onQueue('spot3');
        }
    }
}
