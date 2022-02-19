<?php

namespace App\Console\Commands\V1;

use Illuminate\Console\Command;

class OutageServiceNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:outage_service_notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run every day at 5am sending server outage notifications to clients';

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
    }
}
