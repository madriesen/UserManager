<?php

namespace App\Events\Invite;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Accepted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $invite_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $invite_id)
    {
        $this->invite_id = $invite_id;
    }
}
