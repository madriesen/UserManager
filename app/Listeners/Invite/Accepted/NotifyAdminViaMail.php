<?php

namespace App\Listeners\Invite\Accepted;

use App\Events\Invite\Accepted;


class NotifyAdminViaMail
{
    /**
     * Handle the event.
     *
     * @param  Created  $event
     * @return void
     */
    public function handle(Accepted $event)
    {
        // send email
        // dump('sending Invite accepted notification email to admin...');
    }
}
