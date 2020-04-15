<?php

namespace App\Repositories\interfaces;

use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\Http\Requests\Api\MemberRequest\ResponseMemberRequest;

interface MemberRequestRepositoryInterface
{
    /**
     * @param CreateMemberRequestRequest $request
     */
    public function create(CreateMemberRequestRequest $request);

    /**
     * @param int $member_request_id
     */
    public function findById(int $member_request_id);

    /**
     * @param int $member_request_id
     * @param ResponseMemberRequest $request
     */
    public function approveById(int $member_request_id, ResponseMemberRequest $request);

    /**
     * @param int $member_request_id
     */
    public function refuseById(int $member_request_id);

    /**
     * @return mixed
     */
    public function all();
}