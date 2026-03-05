<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConfigAckEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

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
