<?php


namespace App\Repositories\interfaces;


use App\Email;

interface EmailRepositoryInterface
{
    /**
     * @param Int $member_request_id
     * @param string $address
     */
    public function createByMemberRequest(Int $member_request_id, string $address): void;

    /**
     * @param string $address
     * @return Email
     */
    public function findByAddress(string $address): Email;

    /**
     * @param Int $id
     * @return Email
     */
    public function findById(Int $id): Email;

    /**
     * @param Int $member_request_id
     * @return Email
     */
    public function findByMemberRequestId(Int $member_request_id): Email;

    /**
     * @param Int $invite_id
     * @return Email
     */
    public function findByInviteId(Int $invite_id): Email;

    /**
     * @param Int $account_id
     * @return Email
     */
    public function findByAccountId(Int $account_id): Email;
}