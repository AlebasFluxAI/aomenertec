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
    protected $signature = 'command:enertec:v1:process_job {number}';

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
        DB::table('failed_jobs')->orderBy('failed_at')->chunk($this->argument('number'), function ($jobs) {
            foreach ($jobs as $job) {
                try {
                    Artisan::call("queue:retry", ["id" => $job->uuid]);
                } catch (\Throwable $error) {
                    print("Error -> " . $job->uuid . " \n");
                    continue;
                }
                print("Success -> " . $job->uuid . " " . Artisan::output() . " \n");
            }
        });
    }


}
