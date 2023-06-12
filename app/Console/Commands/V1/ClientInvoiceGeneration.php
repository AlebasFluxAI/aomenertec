<?php

namespace App\Console\Commands\V1;

use App\Jobs\GenerateAdminInvoiceJob;
use App\Jobs\V1\Enertec\ClientInvoiceGenerationJob;
use App\Models\V1\Admin;
use App\Models\V1\BillableItem;
use App\Models\V1\Client;
use App\Models\V1\ClientType;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\User;
use App\Models\V1\ZniLevelFee;
use App\Notifications\Alert\ServerAlertNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
            if ($client->clientConfiguration->billing_day != now()->day) {
                return;
            }
            dispatch(new ClientInvoiceGenerationJob($client));
        }
    }


}
