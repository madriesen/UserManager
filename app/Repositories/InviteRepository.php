<?php


namespace App\Repositories;


use App\Events\Invite as InviteEvent;
use App\Invite;
use App\Repositories\Interfaces\InviteRepositoryInterface;
use App\Repositories\Interfaces\MemberRequestRepositoryInterface;
use Illuminate\Support\Facades\Date;

class InviteRepository implements InviteRepositoryInterface
{

    private MemberRequestRepositoryInterface $member_request_repository;

    /**
     * @inheritDoc
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

    /**
     * @inheritDoc
     */
    public function acceptById(Int $invite_id)
    {
        $invite = $this->findById($invite_id);
        $invite->accepted_at = Date::now()->toImmutable();
        $invite->save();

        event(new InviteEvent\Accepted($invite_id));
    }

    /**
     * @inheritDoc
     */
    public function declineById(Int $invite_id)
    {
        $invite = $this->findById($invite_id);
        $invite->declined_at = Date::now()->toImmutable();
        $invite->save();
    }

    /**
     * @inheritDoc
     */
    public function findById(Int $invite_id)
    {
        return Invite::find($invite_id);
    }

    /**
     * @inheritDoc
     */
    public function getHeighestId()
    {
        return Invite::max('id');
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return Invite::all();
    }
}