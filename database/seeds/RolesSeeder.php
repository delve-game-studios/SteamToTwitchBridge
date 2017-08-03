<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'title' => 'Super Administrator',
            'level' => 100,
        ]);

        Role::create([
            'title' => 'Administrator',
            'level' => 99,
        ]);

        Role::create([
            'title' => 'Moderator',
            'level' => 98,
        ]);

        Role::create([
            'title' => 'Subscriber',
            'level' => 97,
        ]);

        Role::create([
            'title' => 'User',
            'level' => 96,
        ]);
    }
}
