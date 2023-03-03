<?php

namespace App\Console\Commands\V1;

use App\Jobs\GenerateAdminInvoiceJob;
use App\Jobs\V1\Enertec\Report\ClientReportSendJob;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\User;
use App\Notifications\Alert\ServerAlertNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ClientReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:client_report {rate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando envia reportes automaticos al cliente con relacion a la periodiciada';

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
        dispatch(new ClientReportSendJob($this->argument('rate')))->onQueue("spot");
    }


}
