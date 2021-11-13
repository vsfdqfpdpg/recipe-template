<?php

namespace Database\Seeders;

use App\Models\Backend\Permission;
use App\Models\Backend\Role;
use App\Models\Recipe;
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
        // Recipe::factory(100)->create();
        Role::factory(100)->create();
        Permission::factory(100)->create();
        // \App\Models\User::factory(10)->create();
        // $this->call([
        //     RoleSeeder::class
        // ]);
    }
}
