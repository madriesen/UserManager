<?php


namespace App\Repositories;


use App\Events\Invite as InviteEvent;
use App\Exceptions\ModelNotFoundException;
use App\Invite;
use App\Repositories\interfaces\InviteRepositoryInterface;
use Illuminate\Support\Facades\Date;

class InviteRepository implements InviteRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function createByMemberRequestId(int $member_request_id): void
    {
        $email = \MemberRequest::findById($member_request_id)->email;
        $invite = Invite::create();
        $invite->email()->save($email);

        event(new InviteEvent\Created($invite->id));
    }

    /**
     * @inheritDoc
     */
    public function acceptById(int $invite_id): void
    {
        $invite = $this->findById($invite_id);
        $invite->accepted_at = Date::now()->toImmutable();
        $invite->save();

        event(new InviteEvent\Accepted($invite_id));
    }

    /**
     * @inheritDoc
     */
    public function declineById(int $invite_id): void
    {
        $invite = $this->findById($invite_id);
        $invite->declined_at = Date::now()->toImmutable();
        $invite->save();
    }

    /**
     * @inheritDoc
     */
    public function findById(int $invite_id): Invite
    {
        return Invite::find($invite_id);
    }

    /**
     * @inheritDoc
     */
    public function getHighestId(): int
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

    /**
     * @inheritDoc
     * @throws ModelNotFoundException
     */
    public function findByEmailAddress(string $address): Invite
    {
        $email = \Email::findByAddress($address);
        if (empty($email->invite)) throw new ModelNotFoundException('No invite found for ' . $address);
        return $email->invite;

    }
}