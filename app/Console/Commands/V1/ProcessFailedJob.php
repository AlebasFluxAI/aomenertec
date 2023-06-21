<?php

namespace App\Console\Commands\V1;

use App\Jobs\GenerateAdminInvoiceJob;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\User;
use App\Notifications\Alert\ServerAlertNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProcessFailedJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:process_job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reintenta trabajos fallidos';

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
        foreach ($failed = DB::select("select * from failed_jobs order by failed_at desc limit 1000") as $job) {
            Artisan::call("queue:retry", ["id" => $job]);
        }
    }


}
