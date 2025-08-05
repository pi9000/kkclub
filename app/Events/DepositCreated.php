<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DepositCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notif;

    /**
     * Create a new event instance.
     */
    public function __construct($notif)
    {
        $this->notif = $notif;
    }

    public function broadcastOn()
    {
        return ['deposit-channel'];
    }

    public function broadcastAs()
    {
        return 'deposit-event';
    }
}
