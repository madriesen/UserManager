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
     * @param string $token
     */
    public function acceptByToken(string $token): void;

    /**
     * @param string $token
     */
    public function declineByToken(string $token): void;

    /**
     * @param int $invite_id
     * @return Invite
     */
    public function findById(int $invite_id): Invite;

    /**
     * @param string $token
     * @return Invite
     */
    public function findByToken(string $token): Invite;

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