<?php

namespace App\Events\Invite;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Int $invite_id;

    /**
     * Create a new event instance.
     *
     * @param Int $invite_id
     */
    public function __construct(Int $invite_id)
    {
        $this->invite_id = $invite_id;
    }

}
