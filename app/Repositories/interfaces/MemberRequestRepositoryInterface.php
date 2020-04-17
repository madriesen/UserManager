<?php

namespace App\Repositories\interfaces;

use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\Http\Requests\Api\MemberRequest\ResponseMemberRequest;
use App\MemberRequest;

interface MemberRequestRepositoryInterface
{
    /**
     * @param CreateMemberRequestRequest $request
     * @return void
     */
    public function create(CreateMemberRequestRequest $request): void;

    /**
     * @param int $member_request_id
     * @return MemberRequest
     */
    public function findById(int $member_request_id): MemberRequest;

    /**
     * @param int $member_request_id
     * @param ResponseMemberRequest $request
     * @return void
     */
    public function approveById(int $member_request_id, ResponseMemberRequest $request): void;

    /**
     * @param int $member_request_id
     * @return void
     */
    public function refuseById(int $member_request_id): void;

    /**
     * @param string $address
     * @return MemberRequest
     */
    public function findByEmailAddress(string $address): MemberRequest;

    /**
     * @return mixed
     */
    public function all();
}