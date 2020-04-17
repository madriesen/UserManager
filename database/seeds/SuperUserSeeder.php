<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $member_request = \App\MemberRequest::create();
        $email = $member_request->email()->create();
        $email->address = 'admin@test.be';

        $invite = \App\Invite::create();
        $invite->email()->save($email);

        $account = $email->account()->create();
        $this->_setPrimaryEmailAddress($email, $account);
        $this->_setPassword($account, 'test1234');
        \App\AccountType::all()->firstwhere('title', 'administrator')->accounts()->save($account);
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
     * @param \App\Account $account
     * @param string $password
     */
    private function _setPassword(\App\Account $account, string $password): void
    {
        $account->password = Hash::make($password);
        $account->save();
    }
}
