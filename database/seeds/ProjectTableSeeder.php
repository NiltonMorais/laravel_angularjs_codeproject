<?php

use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    public function run()
    {
        // \CodeProject\Entities\Project::truncate();
        factory(\CodeProject\Entities\Project::class, 10)->create();
    }
}
