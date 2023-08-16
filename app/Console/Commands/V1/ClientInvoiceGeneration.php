<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\ClientInvoiceGenerationJob;
use App\Models\V1\Client;
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
        foreach (Client::get() as $client) {
            if ($client->clientConfiguration->billing_day + 1 != now()->day) {
                return;
            }
            dispatch(new ClientInvoiceGenerationJob($client));
        }
    }


}
