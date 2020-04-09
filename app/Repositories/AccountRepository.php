<?php


namespace App\Repositories;


use App\Invite;
use App\Repositories\interfaces\AccountRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountRepository implements AccountRepositoryInterface
{

    public function createByInviteId(Int $invite_id)
    {
        $email = Invite::find($invite_id)->first()->email;
        $email->account()->create(['primary_email_id' => $email->id, 'password' => Hash::make(Str::random(32))]);
    }
}