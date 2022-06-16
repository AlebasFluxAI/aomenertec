<?php

namespace App\Notifications;

class WhatsAppMessage
{
    public $template_name;
    public $params;
    public $to;
    public $lang;

    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    public function template_name($template_name)
    {
        $this->template_name = $template_name;

        return $this;
    }

    public function params($params)
    {
        $this->params = $params;

        return $this;
    }
}
