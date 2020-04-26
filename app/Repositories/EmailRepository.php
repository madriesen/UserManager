<?php


namespace App\Repositories;

use App\Email;
use App\Exceptions\ModelNotFoundException;
use App\Repositories\interfaces\EmailRepositoryInterface;


class EmailRepository implements EmailRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function createByMemberRequest(string $uuid, string $address): void
    {
        $email = \MemberRequest::findByUUID($uuid)->email()->create();
        $email->address = $address;
        $email->save();
    }

    /**
     * @inheritDoc
     * @throws ModelNotFoundException
     */
    public function findByAddress(string $address): Email
    {
        $email = Email::all()->firstWhere('address', $address);
        if (empty($email)) throw new ModelNotFoundException('no email found with address: ' . $address . 'in: ' . Email::all());
        return $email;
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id): Email
    {
        return Email::find($id);
    }

    /**
     * @inheritDoc
     */
    public function findByMemberRequestId(int $member_request_id): Email
    {
        return \MemberRequest::findById($member_request_id)->email;
    }

    /**
     * @inheritDoc
     */
    public function findByInviteId(int $invite_id): Email
    {
        return \Invite::findById($invite_id)->email;
    }

    /**
     * @inheritDoc
     */
    public function findByAccountId(int $account_id): Email
    {
        return \Account::findById($account_id)->email;
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return Email::all();
    }
}