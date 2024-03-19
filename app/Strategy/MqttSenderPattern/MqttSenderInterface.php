<?php

namespace App\Strategy\MqttSenderPattern;

use Livewire\Component;
use PhpMqtt\Client\MqttClient;

interface MqttSenderInterface
{
    public function __construct(MqttClient $mqttClient, Component $component);

    public function subscribeContext($message, $equipment, $notificacionTypeId);

    public function registerLoopEventHandlerContext(float $elapsedTime, MqttClient $mqtt);

    public function registerLoopEventHandler();

    public function subscribe($equipment, $notificacionTypeId);

    public function fetchDataFromAPI($requestDetails);


}
