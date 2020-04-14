<?php

namespace App\Listeners\MemberRequest\Created;

use App\Events\MemberRequest\Created;

class CreateEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Created $event
     * @return void
     */
    public function handle(Created $event)
    {
        \Email::createByMemberRequest($event->member_request->id, $event->request->email_address);
    }
}
