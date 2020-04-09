<?php


namespace App\Repositories;


use App\Invite;
use App\Repositories\Interfaces\InviteRepositoryInterface;
use App\Repositories\Interfaces\MemberRequestRepositoryInterface;

class InviteRepository implements InviteRepositoryInterface
{

    private MemberRequestRepositoryInterface $member_request_repository;

    /**
     * MemberRequestController constructor.
     * @param MemberRequestRepositoryInterface $member_request_repository
     */
    public function __construct(MemberRequestRepositoryInterface $member_request_repository)
    {
        $this->member_request_repository = $member_request_repository;
    }

    public function createByMemberRequestId(Int $member_request_id)
    {
        $email = $this->member_request_repository->findById($member_request_id)->email;
        $invite = Invite::create();
        $invite->email()->save($email);
    }
}