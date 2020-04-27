<?php

namespace App\Events\Invite;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $invite_uuid;

    /**
     * Create a new event instance.
     *
     * @param string $invite_uuid
     */
    public function __construct(string $invite_uuid)
    {
        $this->invite_uuid = $invite_uuid;
    }

}
