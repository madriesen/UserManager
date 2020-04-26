<?php

namespace App\Listeners\MemberRequest\Approved;

use App\Events\MemberRequest\Approved;


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
        \Invite::createByMemberRequestUUID($event->uuid);
    }
}
