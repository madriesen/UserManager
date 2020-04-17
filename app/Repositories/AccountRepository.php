<?php


namespace App\Repositories;


use App\Account;
use App\AccountType;
use App\Exceptions\ModelNotFoundException;
use App\Repositories\interfaces\AccountRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountRepository implements AccountRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function createByInviteId(int $invite_id): void
    {
        $email = \Invite::findByID($invite_id)->email;
        $account = $email->account()->create();
        $this->_setPrimaryEmailAddress($email, $account);
        $this->_setPassword($account, Str::random(32));
        AccountType::all()->firstwhere('title', 'default')->accounts()->save($account);
    }

    /**
     * @inheritDoc
     */
    public function findById(int $account_id): Account
    {
        return Account::find($account_id);
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return Account::all()->map->format();
    }

    /**
     * @inheritDoc
     */
    public function findByPrimaryEmailAddress(string $email_address): Account
    {
        $account = Account::all()->firstWhere('primary_email_id', \Email::findByAddress($email_address)->id);
        if (empty($account)) throw new ModelNotFoundException('No account found with primary email address: ' . $email_address);
        return $account;
    }

    /**
     * @inheritDoc
     */
    public function findByPrimaryEmailAddressId(int $email_id): Account
    {
        return Account::all()->firstWhere('primary_email_id', \Email::findById($email_id)->id);
    }

    /**
     * @inheritDoc
     */
    public function updatePassword(int $id, string $password): void
    {
        $account = $this->findById($id);
        $this->_setPassword($account, $password);
    }

    /**
     * @param $email
     * @param $account
     */
    private function _setPrimaryEmailAddress($email, $account): void
    {
        $account->primary_email_id = $email->id;
        $account->save();
    }

    /**
     * @param Account $account
     * @param string $password
     */
    private function _setPassword(Account $account, string $password): void
    {
        $account->password = Hash::make($password);
        $account->save();
    }

    public function getHighestId(): int
    {
        return Account::max('id');
    }
}