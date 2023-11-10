<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserNotificationEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    use InteractsWithBroadcasting;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $type;
    private $user_id;

    public function __construct($type, $user_id)
    {
        $this->type = $type;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("notifications");
    }

    public function broadcastAs()
    {
        return $this->type;
    }

    public function broadcastWith()
    {
        return [
            "notifiable" => $this->user_id,
        ];
    }
}
