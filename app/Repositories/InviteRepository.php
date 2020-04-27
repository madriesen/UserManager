<?php


namespace App\Repositories;


use App\Events\Invite as InviteEvent;
use App\Exceptions\ArgumentNotSetException;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\Api\Invite\CreateInviteRequest;
use App\Invite;
use App\Repositories\interfaces\InviteRepositoryInterface;
use Illuminate\Support\Str;

class InviteRepository implements InviteRepositoryInterface
{
    private Invite $invite;

    /**
     * @inheritDoc
     */
    public function createByMemberRequestUUID(CreateInviteRequest $request): string
    {
        $member_request = $this->_chkMemberRequest($request->member_request_uuid);

        $this->invite = Invite::create();
        $this->_setUUID();
//        $this->_setToken(Str::random(32));
        $this->_setEmail($member_request->email);

        event(new InviteEvent\Created($this->invite->uuid));

        return $this->invite->uuid;
    }

    /**
     * @param string|null $member_request_uuid
     * @return mixed
     * @throws ArgumentNotSetException
     * @throws InvalidEmailException
     */
    private function _chkMemberRequest(?string $member_request_uuid)
    {
        if (empty($member_request_uuid))
            throw new ArgumentNotSetException('Please, enter a member request');

        try {
            $member_request = \MemberRequest::findByUUID($member_request_uuid);
        } catch (ModelNotFoundException $e) {
            throw new ArgumentNotSetException('Please, enter an existing member request');
        }

        if (!$member_request->approved)
            throw new ArgumentNotSetException('Please, enter an approved member request');

        if (!empty($member_request->email->invite))
            throw new InvalidEmailException('This email is already invited');
        return $member_request;
    }

    /**
     */
    private function _setUUID(): void
    {
        $this->invite->uuid = Str::uuid()->toString();
    }


    /**
     * @param $email
     */
    private function _setEmail($email): void
    {
        $this->invite->email()->save($email);
        $this->invite->save();
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
     * @inheritDoc
     */
    public function acceptByUUID(string $uuid): void
    {
        // TODO: Implement acceptByUUID() method.
    }

    /**
     * @inheritDoc
     */
    public function declineByUUID(string $uuid): void
    {
        // TODO: Implement declineByUUID() method.
    }

    /**
     * @inheritDoc
     */
    public function findByUUID(string $uuid): Invite
    {
        return Invite::all()->firstWhere('uuid', $uuid);
    }
}