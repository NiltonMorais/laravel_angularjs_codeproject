<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        // \CodeProject\Entities\User::truncate();
        factory(\CodeProject\Entities\User::class)->create([
                'name' => "Nilton",
                'email' => "nilton@schoolofnet.com",
                'password' => bcrypt(123456),
                'remember_token' => str_random(10),
            ]);

        factory(\CodeProject\Entities\User::class, 10)->create();
    }
}
