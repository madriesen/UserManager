<?php

namespace App\Events\Invite;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Accepted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $invite;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($invite)
    {
        $this->invite = $invite;
    }
}
