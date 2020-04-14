<?php

namespace App\Listeners\Invite\Accepted;

use App\Events\Invite\Accepted;
use App\Repositories\interfaces\InviteRepositoryInterface;

class CreateAccount
{
    private InviteRepositoryInterface $invite_repository;

    /**
     * Create the event listener.
     *
     * @param InviteRepositoryInterface $invite_repository
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
