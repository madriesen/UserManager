<?php

namespace App\Events\MemberRequest;

use App\Email;
use App\MemberRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Approved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $member_request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MemberRequest $member_request)
    {
        $this->member_request = $member_request;
    }
}
