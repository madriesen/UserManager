<?php

namespace App\Events\MemberRequest;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $member_request;
    public $request;

    /**
     * Create a new event instance.
     *
     * @param $member_request
     * @param $request
     */
    public function __construct($member_request, $request)
    {
        $this->member_request = $member_request;
        $this->request = $request;
    }
}
