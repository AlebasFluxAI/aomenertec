<?php

namespace App\Console\Commands\V1\OrderData;

use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataMonthJob;
use App\Jobs\V1\OrderData\AverageDailyConsumptionJob;
use App\Jobs\V1\OrderData\AverageHourlyConsumptionJob;
use App\Jobs\V1\OrderData\AverageMonthlyConsumptionJob;
use App\Models\V1\Client;
use App\Models\V1\ClientConfiguration;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AverageDaylyConsumptionCommand extends Command
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
    protected $description = 'El comando se ejecuta cada dia a las 1:00. Se encarga de actualizar el consumo diario y de promediar los dias de no registro de datos';


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
        $billing_day = $day_reference->format('d');
        if ($billing_day == $day_reference->format('t')){
            $billing_day_clients = ClientConfiguration::whereBillingDay(31)->get()->pluck('client_id');
        } else{
            $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->orderBy('client_id')->get()->pluck('client_id');
        }
        $clients_aux = Client::whereIn('id', $billing_day_clients)->whereHasTelemetry(true)->select('id')->get()->pluck('id');

        if (count($clients_aux)>0) {
            foreach ($clients_aux as $client) {
                dispatch(new AverageMonthlyConsumptionJob($client->id, $day_reference))->onQueue('spot3');

            }
        }

    }
}
