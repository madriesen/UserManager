<?php

namespace App\Listeners\MemberRequest\Approved;

use App\Events\MemberRequest\Approved;
use App\Http\Requests\Api\Invite\CreateInviteRequest;


class CreateInvite
{
    /**
     * Handle the event.
     *
     * @param Approved $event
     * @return void
     */
    public function handle(Approved $event)
    {
        \Invite::createByMemberRequestUUID(new CreateInviteRequest(['member_request_uuid' => $event->uuid]));
    }
}
