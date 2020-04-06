<?php

namespace App\Listeners\MemberRequest\Approved;

use App\Events\MemberRequest\Approved;
use App\Http\Controllers\Auth\Registration\Invite\InviteController;
use App\Http\Requests\InviteRequest;

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
        $request_data = ['member_request_id' => $event->member_request->id, 'email_id' => $event->member_request->email->id];
        $request = new InviteRequest($request_data);
        (new InviteController)->__invoke($request);
    }
}
