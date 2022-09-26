<?php

namespace App\Jobs\V1;

use App\Models\V1\ClientAlertConfiguration;
use App\Models\V1\EquipmentType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpMqtt\Client\Facades\MQTT;

class SetConfigJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $config;
    public function __construct($config)
    {
        $this->config =json_decode($config);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $equipment_serial = str_pad($this->config->did, 6, "0", STR_PAD_LEFT);
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
            ->first();
        if ($equipment == null) {
            return;
        }
        $client = $equipment->clients()->first();
        if ($client == null) {
            return;
        }
        if (isset($this->config->config_get)){
            $alert_config_frame = config('data-frame.alert_config_frame');
            if (!$client->clientAlertConfiguration()->exists()) {
                $flags_frame = collect(config('data-frame.flags_frame'));
                $alerts = $flags_frame->where('id', '>=', 16)->all();
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
            }
            $topic = "mc/config/" . $equipment_serial;
            $binary_data = [];
            $data = "";
            foreach ($alert_config_frame as $item) {
                if ($item['variable_name'] == 'network_operator_id') {
                    $data = $client->networkOperator->identification;
                } elseif ($item['variable_name'] == 'equipment_id') {
                    $data = $equipment->serial;
                } elseif ($item['variable_name'] == 'network_operator_new_id') {
                    $data = $client->networkOperator->identification;
                } elseif ($item['variable_name'] == 'equipment_new_id') {
                    $data = $equipment->serial;
                } else {
                    $aux_variable = $client->clientAlertConfiguration()->where('flag_id', $item['flag_id'])->first();
                    $data = $aux_variable->{$item['limit']};
                }
                array_push($binary_data, pack($item['type'], $data));
            }
            $message = base64_encode(implode($binary_data));
            MQTT::publish($topic, $message);
            MQTT::disconnect();
        }

    }
}
