<?php

namespace App\Listeners\Invite\Created;

use App\Events\Invite\Created;
use App\Mail\InviteMail;
use Illuminate\Support\Facades\Mail;

class SendInviteEmail
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
        $email_address = \Invite::findByUUID($event->invite_uuid)->email->address;
        Mail::to($email_address)->send(new InviteMail($event->invite_uuid));
    }
}
