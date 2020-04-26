<?php

namespace App\Repositories\interfaces;

use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\MemberRequest;
use Exception;

interface MemberRequestRepositoryInterface
{
    /**
     * @param CreateMemberRequestRequest $request
     * existing of email_address, name, first_name
     * @return string
     * @throws Exception
     */
    public function create(CreateMemberRequestRequest $request): string;

    /**
     * @param string $uuid
     * @return MemberRequest
     */
    public function findByUUID(string $uuid): MemberRequest;

    /**
     * @param string $uuid
     * @return void
     */
    public function approveByUUID(string $uuid): void;

    /**
     * @param string $uuid
     * @return void
     */
    public function refuseByUUID(string $uuid): void;

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