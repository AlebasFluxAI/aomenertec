<?php

namespace App\Strategy\MqttSenderPattern;

use PhpMqtt\Client\MqttClient;

class TestMqttSender implements MqttSenderInterface
{
    use MqttSenderTrait;


    public function subscribeContext($message)
    {
        // Implementa la lógica para suscribirse al contexto aquí
    }

    public function registerLoopEventHandlerContext(float $elapsedTime, MqttClient $mqtt)
    {
        // Implementa la lógica para registrar un controlador de bucle de evento aquí
    }


    public function setMessage()
    {
        // Implementa la lógica para establecer el mensaje aquí
    }

    public function setTopic()
    {
        // Implementa la lógica para establecer el tema aquí
    }
}
