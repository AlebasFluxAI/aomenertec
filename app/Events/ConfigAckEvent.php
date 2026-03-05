<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConfigAckEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    use Queueable;

    public $data;

    /**
     * @param array $data [
     *     'client_id' => int,
     *     'serial' => string,
     *     'event' => string (e.g. "set-sampling-time"),
     *     'event_log_id' => int,
     *     'status' => string ("success"|"error"),
     *     'message' => string,
     * ]
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new Channel('config-ack.' . $this->data['client_id']);
    }

    public function broadcastAs()
    {
        return 'configAckResponse';
    }

    public function broadcastWith()
    {
        return ['data' => $this->data];
    }
}
