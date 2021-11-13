<?php

namespace Database\Seeders;

use App\Models\Backend\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['title' => 'Admin', 'slug' => 'admin', 'description' => 'Admin', 'active' => true]);
        Role::create(['title' => 'Editor', 'slug' => 'editor', 'description' => 'Editor Recipe', 'active' => true]);
        Role::create(['title' => 'Blocked', 'slug' => 'blocked', 'description' => 'Blocked user', 'active' => true]);
    }
}
