<?php

namespace App\Strategy\MqttSenderPattern;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use PhpMqtt\Client\MqttClient;

trait MqttSenderTrait
{
    private $mqtt;
    private $component;

    public function __construct(MqttClient $mqtt, Component $component)
    {
        $this->mqtt = $mqtt;
        $this->component = $component;
    }

    public function fetchDataFromAPI($requestDetails)
    {
        if($requestDetails['method'] == 'GET') {
            Http::withHeaders([
                'x-api-key' => $requestDetails['apiKey'],
            ])->withoutVerifying()->get($requestDetails['url'], $requestDetails['body']);
        } elseif($requestDetails['method'] == 'POST'){
            Http::withHeaders([
                'x-api-key' => $requestDetails['apiKey'],
            ])->withoutVerifying()->post($requestDetails['url'], $requestDetails['body']);
        }
    }

    public function registerLoopEventHandler()
    {
        $this->mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) {
            $this->registerLoopEventHandlerContext($elapsedTime, $mqtt);
        });
    }

    public function subscribe($equipment, $notificacionTypeId)
    {
        $this->mqtt->subscribe('aom/chanel', function (string $topic, string $message) use ($equipment, $notificacionTypeId) {
            $this->subscribeContext($message, $equipment, $notificacionTypeId);
        }, 1);
        $this->mqtt->loop(true);
        $this->mqtt->disconnect();
    }
}
