<?php

namespace App\Jobs\V1;

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
    public $equipment_serial;
    public function __construct($equipment_serial)
    {
        $this->equipment_serial = str_pad($equipment_serial, 6, "0", STR_PAD_LEFT);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($this->equipment_serial)
            ->first();
        if ($equipment == null) {
            return;
        }
        $client = $equipment->clients()->first();
        if ($client == null) {
            return;
        }
        if (!$client->clientAlertConfiguration()->exists()){
            return;
        }
        $alert_config_frame = config('data-frame.alert_config_frame');
        $topic = "mc/config/" . $this->equipment_serial;
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
