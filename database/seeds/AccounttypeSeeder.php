<?php

use App\AccountType;
use Illuminate\Database\Seeder;

class AccounttypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountType::create(['title' => 'administrator']);
        AccountType::create(['title' => 'user']);
    }
}
