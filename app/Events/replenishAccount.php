<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class replenishAccount
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mailTo;
    public $mailFrom;
    public $mailSubject;
    public $money;

    /**
     * Create a new event instance.
     *
     * @param string $mailTo
     * @param string $mailSubject
     * @param int $money
     */
    public function __construct(string $mailTo, string $mailSubject, int $money)
    {
        $this->mailTo = $mailTo;
        $this->mailSubject = $mailSubject;
        $this->money = $money;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
