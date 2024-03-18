<?php

namespace App\Jobs\V1\Api\ConfigurationClient;

use App\Models\V1\Client;
use App\Models\V1\ClientAlertConfiguration;
use App\Models\V1\Api\EventLog;
use App\ModulesAux\MQTT;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class SaveAlertConfigurations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $json;
    public function __construct($json)
    {
        $this->json = $json;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(array_key_exists('id_event_log', $this->json)) {
            $eventLog = EventLog::find($this->json['id_event_log']);
            if ($eventLog == null) {
                return;
            }
            $requestJson = json_decode($eventLog->request_json);
            $json = $requestJson->frame_alerts;
            if (array_key_exists('serial', $this->json)) {
                $client = Client::getClientFromSerial($this->json['serial']);
                if ($client == null) {
                    return;
                }
                $clientAlert = $client->clientAlertConfiguration()->get();
                if (!$client->clientAlertConfiguration()->exists()) {
                    $flags_frame = collect(config('data-frame.flags_frame'));
                    $alerts = $flags_frame->filter(function ($item) {
                        return $item['id'] >= 16;
                    })->all();
                    foreach ($alerts as $item) {
                        ClientAlertConfiguration::create([
                            "client_id" => $client->id,
                            "flag_id" => $item['id'],
                            "min_alert" => 0,
                            "max_alert" => 0,
                            "min_control" => 0,
                            "max_control" => 0,
                            "active_control" => false,
                        ]);
                    }
                    $clientAlert = $client->clientAlertConfiguration()->get();
                }
                $alert_config_frame = collect(config('data-frame.alert_config_frame'));
                foreach ($clientAlert as $alert) {
                    $limits = $alert_config_frame->where('flag_id', $alert->flag_id)->all();
                    foreach ($limits as $limit){
                        $alert->{$limit['limit']} = $json->{$limit['variable_name']};
                    }
                    $alert->save();
                }
            }
        }
    }
}
