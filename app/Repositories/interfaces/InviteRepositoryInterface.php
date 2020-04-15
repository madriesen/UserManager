<?php

namespace App\Repositories\interfaces;

interface InviteRepositoryInterface
{
    /**
     * @param int $member_request_id
     * @return mixed
     */
    public function createByMemberRequestId(int $member_request_id);

    /**
     * @param int $invite_id
     * @return mixed
     */
    public function acceptById(int $invite_id);

    /**
     * @param int $invite_id
     * @return mixed
     */
    public function declineById(int $invite_id);

    /**
     * @param int $invite_id
     * @return mixed
     */
    public function findById(int $invite_id);

    /**
     * @return mixed
     */
    public function getHeighestId();

    /**
     * @return mixed
     */
    public function all();
}