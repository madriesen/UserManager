<?php

use App\Account;
use App\AccountType;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounttype = AccountType::find(1);

        $accounttype->accounts()->create();
    }
}
