<?php


namespace App\Repositories;


use App\Events\Invite as InviteEvent;
use App\Exceptions\ModelNotFoundException;
use App\Invite;
use App\Repositories\interfaces\InviteRepositoryInterface;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class InviteRepository implements InviteRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function createByMemberRequestId(int $member_request_id): void
    {
        $email = \MemberRequest::findById($member_request_id)->email;
        $invite = Invite::create();
        $this->_setToken($invite, Str::random(32));
        $this->_setEmail($invite, $email);

        event(new InviteEvent\Created($invite->id));
    }

    /**
     * @inheritDoc
     */
    public function acceptByToken(string $token): void
    {
        $invite = $this->findByToken($token);
        $invite->accepted_at = Date::now()->toImmutable();
        $invite->save();

        event(new InviteEvent\Accepted($invite->id));
    }

    /**
     * @inheritDoc
     */
    public function declineByToken(string $token): void
    {
        $invite = $this->findByToken($token);
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


    /**
     * @param string $token
     * @param Invite $invite
     */
    private function _setToken(Invite $invite, string $token): void
    {
        $invite->token = $token;
        $invite->save();
    }

    /**
     * @param $invite
     * @param $email
     */
    private function _setEmail($invite, $email): void
    {
        $invite->email()->save($email);
        $invite->save();
    }

    public function findByToken(string $token): Invite
    {
        return Invite::all()->firstWhere('token', $token);
    }
}