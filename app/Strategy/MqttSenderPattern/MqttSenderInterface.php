<?php

namespace App\Strategy\MqttSenderPattern;

use PhpMqtt\Client\MqttClient;

interface MqttSenderInterface
{
    public function subscribeContext($message);

    public function registerLoopEventHandlerContext(float $elapsedTime, MqttClient $mqtt);

    public function registerLoopEventHandler();

    public function subscribe();

    public function publish();

    public function setMessage();

    public function setTopic();
}
