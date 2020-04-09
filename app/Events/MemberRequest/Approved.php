<?php

namespace App\Events\MemberRequest;

use App\Email;
use App\Http\Requests\Api\MemberRequest\ResponseMemberRequest;
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
    public $request;

    /**
     * Create a new event instance.
     *
     * @param MemberRequest $member_request
     * @param ResponseMemberRequest $request
     */
    public function __construct(MemberRequest $member_request, ResponseMemberRequest $request)
    {
        $this->member_request = $member_request;
        $this->request = $request;
    }
}
