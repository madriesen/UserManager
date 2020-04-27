<?php

namespace App\Repositories\interfaces;

use App\Http\Requests\Api\Invite\CreateInviteRequest;
use App\Invite;

interface InviteRepositoryInterface
{
    /**
     * @param CreateInviteRequest $request
     * @return string
     */
    public function createByMemberRequestUUID(CreateInviteRequest $request): string;

    /**
     * @param string $uuid
     */
    public function acceptByUUID(string $uuid): void;

    /**
     * @param string $uuid
     */
    public function declineByUUID(string $uuid): void;

    /**
     * @param int $invite_id
     * @return Invite
     */
    public function findById(int $invite_id): Invite;

    /**
     * @param string $uuid
     * @return Invite
     */
    public function findByUUID(string $uuid): Invite;

    /**
     * @param string $address
     * @return Invite
     */
    public function findByEmailAddress(string $address): Invite;

    /**
     * @return int
     */
    public function getHighestId(): int;

    /**
     * @return mixed
     */
    public function all();
}