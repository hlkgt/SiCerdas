<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckTokenEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $message;
    protected $status;
    protected $user_request;
    /**
     * Create a new event instance.
     */
    public function __construct(string $message, bool $status, int $user_id)
    {
        $this->message = $message;
        $this->status = $status;
        $this->user_request = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('check-token');
    }

    public function broadcastWith(): array
    {
        return ["message" => $this->message, "status" => $this->status, "user_id" => $this->user_request];
    }
}
