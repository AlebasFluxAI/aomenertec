<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Throwable;

class WhatsAppChannel
{
    private $httpClient;

    /**
     * Send the given notification.
     */
    public function __construct()
    {
        $this->httpClient = Http::withToken(config('whatsapp.api_key'), 'AccessKey')->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    public function send($notifiable, Notification $notification)
    {
        $toWhatsapp = $notification->toWhatsapp($notifiable);
        if (!$this->checkTemplateExists($toWhatsapp->template_name)) {
            return;
        }

        if (!config('whatsapp.api_key')) {
            return;
        }
        $cellphone = $toWhatsapp->to;

        try {
            $body = [
                'to' => (int)$notifiable->indicative . $notifiable->phone,
                'channelId' => config('whatsapp.channel_id'),
                'type' => 'hsm',
                'content' => [
                    'hsm' => [
                        'namespace' => config('whatsapp.namespace'),
                        'templateName' => $toWhatsapp->template_name,
                        'language' => [
                            'policy' => 'deterministic',
                            'code' => 'es',
                        ],
                        'params' => $this->getParams($toWhatsapp->params),
                    ],
                ],
            ];


            $response = $this->httpClient->post(
                'https://conversations.messagebird.com/v1/conversations/start',
                $body
            );
        } catch (Throwable $e) {
        }
    }

    public function checkTemplateExists($template)
    {
        try {
            $response = $this->httpClient->get(
                'https://integrations.messagebird.com/v1/public/whatsapp/templates'
            );
        } catch (Throwable $e) {
            return false;
        }
        return in_array($template, collect($response->object())->where('status', 'APPROVED')->pluck('name')->toArray());
    }

    public function getParams($params_in)
    {
        $params = [];

        foreach ($params_in as $param) {
            array_push($params, ['default' => strval('' == $param ? '_' : $param)]);
        }

        return $params;
    }

    public function sendAttachInvoice($toWhatsapp)
    {
        if (!config('whatsapp.api_key')) {
            return;
        }
        $cellphone = $toWhatsapp->to;


        try {
            $response = $this->httpClient->post(
                'https://conversations.messagebird.com/v1/send',
                [
                    'to' => $cellphone,
                    'channelId' => config('whatsapp.channel_id'),
                    'type' => 'hsm',
                    'content' => [
                        'hsm' => [
                            'namespace' => config('whatsapp.namespace'),
                            'templateName' => $toWhatsapp->template_name,
                            'language' => [
                                'policy' => 'deterministic',
                                'code' => 'es',
                            ],
                            'components' => [
                                [
                                    'type' => 'header',
                                    'parameters' => [
                                        [
                                            'type' => 'document',
                                            'document' => [
                                                'url' => $toWhatsapp->params[1],
                                                'caption' => 'Factura ' . $toWhatsapp->params[0],
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'type' => 'body',
                                    'parameters' => [
                                        [
                                            'type' => 'text',
                                            'text' => $toWhatsapp->params[0],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            );
        } catch (Throwable $e) {
        }
    }
}
