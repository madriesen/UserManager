<?php

namespace App\Repositories\interfaces;

use App\Invite;

interface InviteRepositoryInterface
{
    /**
     * @param int $member_request_id
     * @return void
     */
    public function createByMemberRequestId(int $member_request_id): void;

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