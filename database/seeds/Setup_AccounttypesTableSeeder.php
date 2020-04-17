<?php

use Illuminate\Database\Seeder;

class Setup_AccounttypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['title' => 'administrator', 'description' => 'superuser'],
            ['title' => 'user_manager', 'description' => 'allowed to respond to member requests'],
            ['title' => 'default', 'description' => 'default user'],
        ];

        foreach ($types as $type) \AccountType::create($type['title'], $type['description']);
    }
}
