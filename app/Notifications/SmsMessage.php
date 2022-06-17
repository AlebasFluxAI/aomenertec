<?php

namespace App\Notifications;

class SmsMessage
{
    public $to;
    public $message;

    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    public function message($message)
    {
        $this->message = $message;

        return $this;
    }
}
