<?php

namespace App\Http\Services;

use PhpMqtt\Client\Facades\MQTT;

abstract class MqttConfigSender extends Singleton
{
    public static function sendMessage($payload)
    {
        $message = $payload;
        if (is_array($payload)) {
            $message = json_encode($payload);
        }
        MQTT::publish("mc/confg", $message);
    }
}
