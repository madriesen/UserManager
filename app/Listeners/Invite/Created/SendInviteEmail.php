<?php

namespace App\Listeners\Invite\Created;

use App\Events\Invite\Created;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
        //send email
    }
}
