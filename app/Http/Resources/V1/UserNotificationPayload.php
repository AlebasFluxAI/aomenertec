<?php

namespace App\Http\Resources\V1;

class UserNotificationPayload
{
    public $data;
    private $message;
    private $target;
    private $type;
    private $binding;

    public function __construct($message, $target, $type, $binding_id = null, $binding = null)
    {
        $this->message = $message;
        $this->target = $target;
        $this->type = $type;
        $this->binding_id = $binding_id;
        $this->binding = $binding;
        $this->data = $this->getData();
    }

    public function getData(): array
    {
        return [
            "message" => $this->message,
            "target" => $this->target,
            "type" => $this->type,
            "binding_id" => $this->binding_id,
            "binding" => $this->binding
        ];
    }
}
