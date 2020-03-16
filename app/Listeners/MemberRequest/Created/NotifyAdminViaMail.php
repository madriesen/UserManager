<?php

namespace App\Listeners\MemberRequest\Created;

use App\Events\MemberRequest\Created;

class NotifyAdminViaMail
{
    /**
     * Handle the event.
     *
     * @param  Created  $event
     * @return void
     */
    public function handle(Created $event)
    {
        // send email
        print_r('sending member_request notification email to admin...');
    }
}
