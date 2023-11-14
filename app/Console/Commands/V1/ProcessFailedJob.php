<?php

namespace App\Console\Commands\V1;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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

        foreach (DB::select("select uuid from failed_jobs order by failed_at desc limit " . $this->argument("number")) as $job) {
            try {
                Artisan::call("queue:retry", ["id" => $job->uuid]);
            } catch (\Throwable $error) {
                print("Error -> " . $job->uuid . " \n");
                continue;
            }
            print("Success -> " . $job->uuid . " " . Artisan::output() . " \n");
        }

    }


}
