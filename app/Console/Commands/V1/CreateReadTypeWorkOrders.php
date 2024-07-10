<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\WorkOrder\CreateReadTypeWorkOrderJob;
use App\Models\V1\Client;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateReadTypeWorkOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:create_read_type_work_orders';

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
        $now = Carbon::now();
        $billing_date = $now->copy()->addDays(3);
        if ($billing_date->format('d') == $billing_date->format('t')) {
            $billing_day_clients = ClientConfiguration::whereBillingDay(31)->get()->pluck('client_id');
        } else {
            $billing_day_clients = ClientConfiguration::whereBillingDay($billing_date->format('d'))->orderBy('client_id')->get()->pluck('client_id');
        }
        $clients_aux = Client::whereIn('id', $billing_day_clients)->whereHasTelemetry(true)->select('id')->get();
        $limit_date = $billing_date->copy()->subDays(2);
        foreach ($clients_aux as $client) {
            if (!($client->microcontrollerData()
                ->where('source_timestamp', '>=', $limit_date)
                ->exists())) {
                dispatch(new CreateReadTypeWorkOrderJob($client->id, WorkOrder::WORK_ORDER_TYPE_READING))->onConnection('sync');
            }
        }
    }
}
