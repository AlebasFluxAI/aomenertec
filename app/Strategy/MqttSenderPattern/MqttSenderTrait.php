<?php

namespace App\Strategy\MqttSenderPattern;

use PhpMqtt\Client\MqttClient;

trait MqttSenderTrait
{
    public function registerLoopEventHandler()
    {
        $this->mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) {
            $this->registerLoopEventHandlerContext($elapsedTime, $mqtt);
        });
    }

    public function subscribe()
    {
        $this->mqtt->subscribe($this->topic, function (string $topic, string $message) {
            $this->subscribeContext($message);
        }, 1);
        $this->mqtt->loop(true);
        $this->mqtt->disconnect();
    }
}
