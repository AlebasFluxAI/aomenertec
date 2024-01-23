<?php

namespace App\Strategy\MqttSenderPattern;

use PhpMqtt\Client\MqttClient;

interface MqttSenderInterface
{
    public function subscribeContext($message);

    public function registerLoopEventHandlerContext(float $elapsedTime, MqttClient $mqtt, $params);

    public function registerLoopEventHandler();

    public function subscribe();

    public function publish($topic, $message);

    public function makeMessage();

    public function setTopic();
}
