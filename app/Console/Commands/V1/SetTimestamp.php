<?php

namespace App\Console\Commands\V1;

use App\Http\Resources\V1\TimeZoneHelper;
use App\Models\V1\Client;
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
        $clients = Client::whereHasTelemetry(true)->get();
        foreach ($clients as $client) {
            $equipment = $client->equipments()->whereEquipmentTypeId(1)->first();
            $topic = "mc/config/" . $equipment->serial;
            $date = (new Carbon('now', $client->time_zone));
            $date_unix = (Carbon::parse($date->format('Y-m-d H:i:s'), TimeZoneHelper::COLOMBIA))->timestamp;
            MQTT::publish($topic, $date_unix);
        }
        MQTT::disconnect();
    }
}
