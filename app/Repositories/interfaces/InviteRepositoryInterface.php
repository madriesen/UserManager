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
     * @param int $invite_id
     * @return void
     */
    public function acceptById(int $invite_id): void;

    /**
     * @param int $invite_id
     * @return void
     */
    public function declineById(int $invite_id): void;

    /**
     * @param int $invite_id
     * @return Invite
     */
    public function findById(int $invite_id): Invite;

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