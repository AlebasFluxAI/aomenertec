<?php

namespace App\Console\Commands\V1\OrderData;

use App\Jobs\V1\OrderData\AverageHourlyConsumptionJob;
use App\Models\V1\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AverageHourlyConsumptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:order_data:average_hourly_consumption_command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'El comando se ejecuta cada hora a las H:30. Se encarga de actualizar el consumo horario y de promediar las horas de no registro de datos';

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
        $hour_reference = Carbon::now()->subHour();
        foreach ($clients as $client) {
            dispatch(new AverageHourlyConsumptionJob($client->id, $hour_reference))->onQueue('spot3');
        }
    }
}
