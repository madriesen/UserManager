<?php


namespace App\Repositories\interfaces;


use App\Account;

interface AccountRepositoryInterface
{
    /**
     * @param int $invite_id
     * @return void
     */
    public function createByInviteId(int $invite_id): void;

    /**
     * @param int $account_id
     * @return Account
     */
    public function findById(int $account_id): Account;

    /**
     * @param string $email_address
     * @return Account
     */
    public function findByPrimaryEmailAddress(string $email_address): Account;

    /**
     * @param int $email_id
     * @return Account
     */
    public function findByPrimaryEmailAddressId(int $email_id): Account;

    /**
     * @return Account
     */
    public function all();
}