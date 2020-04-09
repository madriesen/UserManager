<?php

namespace App\Listeners\Invite\Accepted;

use App\Events\Invite\Accepted;
use App\Repositories\Interfaces\InviteRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateAccount
{
    private InviteRepositoryInterface $invite_repository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(InviteRepositoryInterface $invite_repository)
    {
        $this->invite_repository = $invite_repository;
    }

    /**
     * Handle the event.
     *
     * @param Accepted $event
     * @return void
     */
    public function handle(Accepted $event)
    {
        \Account::createByInviteId($event->invite_id);
    }
}
