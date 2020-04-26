<?php

namespace App\Events\MemberRequest;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $uuid;
    public string $email_address;

    /**
     * Create a new event instance.
     *
     * @param string $uuid
     * @param string $email_address
     */
    public function __construct(string $uuid, string $email_address)
    {
        $this->uuid = $uuid;
        $this->email_address = $email_address;
    }
}
