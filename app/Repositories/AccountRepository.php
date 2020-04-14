<?php


namespace App\Repositories;


use App\Account;
use App\AccountType;
use App\Repositories\interfaces\AccountRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountRepository implements AccountRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function createByInviteId(Int $invite_id): void
    {
        $email = \Invite::findByID($invite_id)->email;
        $account = $email->account()->create();
        $account->primary_email_id = $email->id;
        $account->password = Hash::make(Str::random(32));
        $account->save();
        AccountType::all()->firstwhere('title', 'default')->accounts()->save($account);
    }

    /**
     * @inheritDoc
     */
    public function findById(Int $account_id): Account
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
        return Account::all()->firstWhere('primary_email_id', \Email::findByAddress($email_address)->id);
    }

    /**
     * @inheritDoc
     */
    public function findByPrimaryEmailAddressId(Int $email_id): Account
    {
        return Account::all()->firstWhere('primary_email_id', \Email::findById($email_id)->id);
    }
}