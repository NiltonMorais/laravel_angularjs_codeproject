<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        // \CodeProject\Entities\User::truncate();
        factory(\CodeProject\Entities\User::class, 10)->create();
    }
}
