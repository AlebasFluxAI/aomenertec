<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        // Guard FIRST — before any HTTP calls
        if (!config('whatsapp.api_key')) {
            Log::warning('WhatsApp notification skipped: WHATSAPP_API_KEY not configured');
            return;
        }

        $toWhatsapp = $notification->toWhatsapp($notifiable);

        if (!$this->checkTemplateExists($toWhatsapp->template_name)) {
            Log::warning('WhatsApp notification skipped: template not approved', ['template' => $toWhatsapp->template_name]);
            return;
        }

        try {
            $body = [
                'to' => '+' . ltrim($notifiable->indicative, '+') . $notifiable->phone,
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

            Log::info('WhatsApp notification sent', [
                'to' => $notifiable->phone,
                'template' => $toWhatsapp->template_name,
                'status' => $response->status(),
            ]);
        } catch (Throwable $e) {
            Log::error('WhatsApp send failed', [
                'error' => $e->getMessage(),
                'to' => $notifiable->phone ?? 'unknown',
                'template' => $toWhatsapp->template_name ?? 'unknown',
            ]);
        }
    }

    public function checkTemplateExists($template)
    {
        $templates = Cache::remember('whatsapp_approved_templates', 300, function () {
            try {
                $response = $this->httpClient->get(
                    'https://integrations.messagebird.com/v1/public/whatsapp/templates'
                );
                return collect($response->object())
                    ->where('status', 'APPROVED')
                    ->pluck('name')
                    ->toArray();
            } catch (Throwable $e) {
                Log::warning('WhatsApp template check failed', ['error' => $e->getMessage()]);
                return [];
            }
        });
        return in_array($template, $templates);
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
            Log::error('WhatsApp send failed', [
                'error' => $e->getMessage(),
                'to' => isset($toWhatsapp) ? ($toWhatsapp->to ?? 'unknown') : 'unknown',
                'template' => isset($toWhatsapp) ? ($toWhatsapp->template_name ?? 'unknown') : 'unknown',
            ]);
        }
    }
}
