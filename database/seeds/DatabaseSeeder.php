<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Setup_AccounttypesTableSeeder::class);
        $this->call(SuperUserSeeder::class);
    }
}
