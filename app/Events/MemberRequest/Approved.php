<?php

namespace App\Events\MemberRequest;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Approved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $uuid;

    /**
     * Create a new event instance.
     *
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }
}
