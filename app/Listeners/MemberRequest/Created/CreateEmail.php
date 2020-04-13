<?php

namespace App\Listeners\MemberRequest\Created;

use App\Events\MemberRequest\Created;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
//        $email = $event->member_request->email()->create(['address' => $event->request->email_address]);
//        $email->address = $event->request->email_address;
//        $email->save();
    }
}
