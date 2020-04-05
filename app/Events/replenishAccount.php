<?php

namespace App\Events;

use App\Card;
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
    public $mailSubject;
    public $money;
    public $card;

    /**
     * Create a new event instance.
     *
     * @param string $mailTo
     * @param string $mailSubject
     * @param int $money
     * @param Card $card
     */
    public function __construct(string $mailTo, string $mailSubject, int $money, Card $card)
    {
        $this->mailTo = $mailTo;
        $this->mailSubject = $mailSubject;
        $this->money = $money;
        $this->card = $card;
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
