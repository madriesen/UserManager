<?php

namespace App\Listeners\Invite\Created;

use App\Events\Invite\Created;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInvitationMail
{
    /**
     * Handle the event.
     *
     * @param  Created  $event
     * @return void
     */
    public function handle(Created $event)
    {
        // dump('send invite to user...');
    }
}
