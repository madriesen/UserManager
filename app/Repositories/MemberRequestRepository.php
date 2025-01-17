<?php


namespace App\Repositories;


use App\Events\MemberRequest as MemberRequestEvent;
use App\Exceptions\ArgumentNotSetException;
use App\Exceptions\EmailAlreadyExists;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\MemberRequest;
use App\Repositories\interfaces\MemberRequestRepositoryInterface;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class MemberRequestRepository implements MemberRequestRepositoryInterface
{
    private MemberRequest $member_request;

    /**
     * @inheritDoc
     */
    public function create(CreateMemberRequestRequest $request): string
    {
        if (empty($request->email_address))
            throw new ArgumentNotSetException('No email address given');
        if ($this->_chkEmailExists($request->email_address))
            if (Date::now()->diffInDays(\Email::findByAddress($request->email_address)->member_request->refused_at) < 14)
                throw new InvalidEmailException('Email already exists');

        $this->_create();
        $this->_setUUID();
        $this->_setName($request->name);
        $this->_setFirstName($request->first_name);

        event(new MemberRequestEvent\Created($this->member_request->uuid, $request->email_address));

        return $this->member_request->uuid;
    }

    /**
     * @param string $email_address
     * @return bool
     */
    private function _chkEmailExists(string $email_address): bool
    {
        try {
            \Email::findByAddress($email_address);
            return true;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    private function _create(): void
    {
        $this->member_request = MemberRequest::create();
    }

    /**
     *
     */
    private function _setUUID(): void
    {
        $this->member_request->uuid = Str::uuid()->toString();
        $this->member_request->save();
    }

    /**
     * @param string $name
     */
    private function _setName(?string $name): void
    {
        $this->member_request->name = $name;
        $this->member_request->save();
    }

    /**
     * @param string $first_name
     */
    private function _setFirstName(?string $first_name): void
    {
        $this->member_request->first_name = $first_name;
        $this->member_request->save();
    }

    /**
     * @inheritDoc
     */
    public function approveByUUID(string $uuid): void
    {
        $member_request = $this->findByUUID($uuid);
        $member_request->approved_at = Date::now()->toImmutable()->toDateTimeString();
        $member_request->save();

        event(new MemberRequestEvent\Approved($member_request->uuid));
    }

    /**
     * @inheritDoc
     */
    public function findByUUID(string $uuid): MemberRequest
    {
        if (empty(MemberRequest::all()->firstWhere('uuid', $uuid)))
            throw new ModelNotFoundException('No member request with uuid ' . $uuid);

        return MemberRequest::all()->firstWhere('uuid', $uuid);
    }

    /**
     * @inheritDoc
     */
    public function refuseByUUID(string $uuid): void
    {
        $member_request = $this->findByUUID($uuid);
        $member_request->refused_at = Date::now()->toImmutable();
        $member_request->save();
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return MemberRequest::all()->map->format();
    }

    /**
     * @inheritDoc
     */
    public function findByEmailAddress(string $address): MemberRequest
    {
        return \Email::findByAddress($address)->member_request;
    }
}