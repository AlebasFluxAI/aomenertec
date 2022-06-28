<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;

class ClientConfiguration extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        "ssid",
        "wifi_password",
        "mqtt_host",
        "mqtt_port",
        "mqtt_user",
        "mqtt_password",
        "real_time_flag",
        "real_time_latency",
        "storage_latency",
        "digital_outputs"
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


}
