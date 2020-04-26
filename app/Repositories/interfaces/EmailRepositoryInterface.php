<?php


namespace App\Repositories\interfaces;


use App\Email;

interface EmailRepositoryInterface
{
    /**
     * @param string $uuid
     * @param string $address
     */
    public function createByMemberRequest(string $uuid, string $address): void;

    /**
     * @param string $address
     * @return Email
     */
    public function findByAddress(string $address): Email;

    /**
     * @param int $id
     * @return Email
     */
    public function findById(int $id): Email;

    /**
     * @param int $member_request_id
     * @return Email
     */
    public function findByMemberRequestId(int $member_request_id): Email;

    /**
     * @param int $invite_id
     * @return Email
     */
    public function findByInviteId(int $invite_id): Email;

    /**
     * @param int $account_id
     * @return Email
     */
    public function findByAccountId(int $account_id): Email;

    /**
     * @return mixed
     */
    public function all();
}