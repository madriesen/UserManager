<?php


namespace App\Repositories\interfaces;


use App\Account;

interface AccountRepositoryInterface
{
    /**
     * @param Int $invite_id
     * @return void
     */
    public function createByInviteId(Int $invite_id): void;

    /**
     * @param Int $account_id
     * @return Account
     */
    public function findById(Int $account_id): Account;

    /**
     * @param string $email_address
     * @return Account
     */
    public function findByPrimaryEmailAddress(string $email_address): Account;

    /**
     * @param Int $email_id
     * @return Account
     */
    public function findByPrimaryEmailAddressId(Int $email_id): Account;

    /**
     * @return Account
     */
    public function all();
}