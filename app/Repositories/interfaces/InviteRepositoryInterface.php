<?php

namespace App\Repositories\interfaces;

interface InviteRepositoryInterface
{
    /**
     * @param Int $member_request_id
     * @return mixed
     */
    public function createByMemberRequestId(Int $member_request_id);

    /**
     * @param Int $invite_id
     * @return mixed
     */
    public function acceptById(Int $invite_id);

    /**
     * @param Int $invite_id
     * @return mixed
     */
    public function declineById(Int $invite_id);

    /**
     * @param Int $invite_id
     * @return mixed
     */
    public function findById(Int $invite_id);

    /**
     * @return mixed
     */
    public function getHeighestId();

    /**
     * @return mixed
     */
    public function all();
}