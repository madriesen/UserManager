<?php

namespace App\Repositories\Interfaces;

interface InviteRepositoryInterface
{
    public function __construct(MemberRequestRepositoryInterface $member_request_repository);

    /**
     * @param Int $member_request_id
     * @return mixed
     */
    public function createByMemberRequestId(Int $member_request_id);
}