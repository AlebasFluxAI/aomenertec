<?php

namespace App\Strategy\MqttSenderPattern;

use Livewire\Component;
use PhpMqtt\Client\MqttClient;

trait MqttSenderTrait
{
    private $mqtt;
    private $topic;
    private $message;
    private $component;

    public function __construct(MqttClient $mqtt, Component $component)
    {
        $this->mqtt = $mqtt;
        $this->component = $component;
    }

    public function publish()
    {
        $this->mqtt->publish($this->topic, $this->message);

    }

    public function registerLoopEventHandler()
    {
        $this->mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) {
            $this->registerLoopEventHandlerContext($elapsedTime, $mqtt);
        });
    }

    public function subscribe()
    {
        $this->mqtt->subscribe('v1/mc/ack', function (string $topic, string $message) {
            $this->subscribeContext($message);
        }, 1);
        $this->mqtt->loop(true);
        $this->mqtt->disconnect();
    }
}
