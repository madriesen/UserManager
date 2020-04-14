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
     * @param Int $member_request_id
     */
    public function findById(Int $member_request_id);

    /**
     * @param Int $member_request_id
     * @param ResponseMemberRequest $request
     */
    public function approveById(Int $member_request_id, ResponseMemberRequest $request);

    /**
     * @param Int $member_request_id
     */
    public function refuseById(Int $member_request_id);

    /**
     * @return mixed
     */
    public function all();
}