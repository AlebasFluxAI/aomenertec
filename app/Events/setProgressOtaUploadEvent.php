<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class setProgressOtaUploadEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    use Queueable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $progress;
    public $firmware_id;

    public function __construct($progress, $firmware_id)
    {
        $this->progress = $progress;
        $this->firmware_id = $firmware_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('data-ota-upload.' . $this->firmware_id);
    }

    public function broadcastAs()
    {
        return 'dataEventSetProgress';
    }

    public function broadcastWith()
    {
        return ["progress" => $this->progress];
    }
}
