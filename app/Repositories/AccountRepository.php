<?php


namespace App\Repositories;


use App\Account;
use App\Invite;
use App\Repositories\interfaces\AccountRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountRepository implements AccountRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function createByInviteId(Int $invite_id)
    {
        $email = Invite::find($invite_id)->first()->email;
        $account = $email->account()->create();
        $account->primary_email_id = $email->id;
        $account->password = Hash::make(Str::random(32));
        $account->save();
    }

    /**
     * @inheritDoc
     */
    public function findById(Int $account_id)
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
}