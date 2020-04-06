<?php

namespace App\Events\Invite;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Declined
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invite;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($invite)
    {
        $this->invite = $invite;
    }
}