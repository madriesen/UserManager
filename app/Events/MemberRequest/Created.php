<?php

namespace App\Events\MemberRequest;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $member_request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($member_request)
    {
        $this->member_request = $member_request;
    }
}
