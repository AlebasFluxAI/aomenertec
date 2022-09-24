<?php

namespace App\Console\Commands\V1;

use Carbon\Carbon;
use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;

class SetTimestamp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:set_timestamp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set timestamp';

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
        $topic = 'mc/config/timestamp';
        $date = Carbon::now()->timestamp;
        MQTT::publish($topic, $date);
        MQTT::disconnect();
    }
}
