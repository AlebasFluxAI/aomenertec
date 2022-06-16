<?php

namespace App\Http\Resources\V1;

use App\Events\UserNotificationEvent;
use ArrayAccess;

class UserNotificationPayload
{
    public $data;
    private $message;
    private $target;
    private $type;

    public function __construct($message, $target, $type, $client_id = null)
    {
        $this->message = $message;
        $this->target = $target;
        $this->type = $type;
        $this->client_id = $client_id;
        $this->data = $this->getData();
    }

    public function getData(): array
    {
        return [
            "message" => $this->message,
            "target" => $this->target,
            "type" => $this->type,
            "client_id" => $this->client_id
        ];
    }


}
