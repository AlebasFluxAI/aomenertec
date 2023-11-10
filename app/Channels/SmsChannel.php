<?php

namespace App\Channels;

use Aws\Sns\SnsClient;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SmsChannel
{
    private $httpClient;

    /**
     * Send the given notification.
     */
    public function __construct()
    {
        $this->httpClient = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    public function send($notifiable, Notification $notification)
    {
        $toSms = $notification->toSms($notifiable);
        $method = config('country.sms-gateway.method');
        $this->{$method}($toSms);
    }

    public function loginSms($toSms)
    {
        if (!config('loginsms.client_id') || !config('loginsms.client_secret')) {
            return;
        }

        $response = $this->httpClient->post(config('loginsms.tokenUrl'), [
            'client_id' => config('loginsms.client_id'),
            'client_secret' => config('loginsms.client_secret'),
            'grant_type' => 'client_credentials',
        ]);
        $response->throw('login Sms credentials error');
        $this->httpClient->withHeaders(['Authorization' => "Bearer {$response['access_token']}"]);
        $response = $this->httpClient->post(config('loginsms.send_sms'), [
            'to_number' => $this->checkCellphone($toSms->to),
            'content' => $toSms->message,
        ]);

        $response->throw('login Sms sending error');
    }

    public function checkCellphone($cellphone)
    {
        if ('EC' == config('country.code') && '0' == substr($cellphone, 0, 1)) {
            $cellphone = substr($cellphone, 1);
        }

        if (strlen($cellphone) <= config('country.sms-gateway.digits')) {
            return config('country.sms-gateway.prefix') . $cellphone;
        }

        return $cellphone;
    }

    public function yesbpo($toSms)
    {
        if (!config('yesbpo.username') || !config('yesbpo.password')) {
            return;
        }
        $this->httpClient->post(config('yesbpo.send_sms'), [
            'authentication' => [
                'username' => config('yesbpo.username'),
                'password' => config('yesbpo.password'),
            ],
            'messages' => [
                'sender' => 'InfoSMS',
                'recipients' => [
                    'gsm' => $this->checkCellphone($toSms->to),
                ],
                'text' => $toSms->message,
            ],
        ]);
    }

    public function sns($toSms)
    {
        if (!config('sns.key') || !config('sns.secret') || !config('sns.region')) {
            return;
        }

        $SnSclient = new SnsClient([
            'credentials' => [
                'key' => config('sns.key'),
                'secret' => config('sns.secret'),
            ],
            'region' => config('sns.region'),
            'version' => 'latest',
        ]);
        $SnSclient->publish([
            'messageAttributes' => [
                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'string',
                    'StringValue' => 'Transactional',
                ],
            ],
            'Message' => $toSms->message,
            'PhoneNumber' => $this->checkCellphone($toSms->to),
        ]);
    }
}
