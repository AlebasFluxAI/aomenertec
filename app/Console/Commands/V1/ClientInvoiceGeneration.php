<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\ClientInvoiceGenerationJob;
use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataMonthJob;
use App\Models\V1\Client;
use App\Models\V1\ClientConfiguration;
use Illuminate\Console\Command;


class ClientInvoiceGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:invoice_client_generation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitorea la salud del servidor principal';

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
        $now_day = now()->day;
        $billing_day = ($now_day == 29 or $now_day == 30 or $now_day == 31) ? 31 : $now_day;
        $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->get()->pluck('client_id');
        $clients_id = Client::whereIn('id', $billing_day_clients)->whereHasTelemetry(true)->get();
        if (count($clients_id)>0) {
            foreach ($clients_id as $client) {
                dispatch(new ClientInvoiceGenerationJob($client));
            }
        }
    }


}
