<?php


namespace App\Repositories;

use App\Email;
use App\Exceptions\ModelNotFoundException;
use App\Repositories\interfaces\EmailRepositoryInterface;
use Mockery\Exception;


class EmailRepository implements EmailRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function createByMemberRequest(Int $member_request_id, string $address): void
    {
        $email = \MemberRequest::findById($member_request_id)->email()->create();
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
    public function findById(Int $id): Email
    {
        return Email::find($id);
    }

    /**
     * @inheritDoc
     */
    public function findByMemberRequestId(Int $member_request_id): Email
    {
        return \MemberRequest::findById($member_request_id)->email;
    }

    /**
     * @inheritDoc
     */
    public function findByInviteId(Int $invite_id): Email
    {
        return \Invite::findById($invite_id)->email;
    }

    /**
     * @inheritDoc
     */
    public function findByAccountId(Int $account_id): Email
    {
        return \Account::findById($account_id)->email;
    }
}