<?php

namespace App\Listeners\MemberRequest\Approved;

use App\Events\MemberRequest\Approved;
use App\Http\Controllers\Auth\Registration\Invite\InviteController;
use App\Repositories\InviteRepository;
use App\Repositories\MemberRequestRepository;
use Illuminate\Support\Facades\Redirect;

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
        // InviteRepository -> called trough facade
        \Invite::createByMemberRequestId($event->member_request->id);
    }
}
