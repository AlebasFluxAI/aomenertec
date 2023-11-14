<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\ClientInvoiceGenerationManuallyJob;
use App\Models\V1\Client;
use App\Models\V1\ClientConfiguration;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClientInvoicingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:invoice_client_generation_manually {date_init} {clients_id?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'genera facturas a clientes';

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

        $now_day = new Carbon($this->argument('date_init'));
        $clients_id = $this->argument('clients_id');
        $billing_day = ($now_day->format('d') == 29 or $now_day->format('d') == 30 or $now_day->format('d') == 31) ? 31 : $now_day->format('d');
        $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->get()->pluck('client_id');
        if (count($clients_id) > 0) {
            $clients_aux = [];

            foreach ($billing_day_clients as $elemento1) {
                foreach ($clients_id as $elemento2) {
                    if ($elemento1 == $elemento2) {
                        $clients_aux[] = $elemento1;
                        break;
                    }
                }
            }
            $clients = Client::find($clients_aux);
        } else {
            $clients = Client::whereIn('id', $billing_day_clients)->whereHasTelemetry(true)->get();
        }
        $now_day->addDay();
        if (count($clients) > 0) {
            foreach ($clients as $client) {
                dispatch(new ClientInvoiceGenerationManuallyJob($client, $now_day))->onQueue('default');
            }
        }
    }

}
