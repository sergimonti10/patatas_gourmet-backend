<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Hay que comentar esto, y luego hacer un migrate solo de los guards_api
        // Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        // Role::create(['name' => 'admin', 'guard_name' => 'web']);
        // Role::create(['name' => 'user', 'guard_name' => 'web']);

        //Comentar esto si vas a usar el UserSeeder
        Role::create(['name' => 'super-admin', 'guard_name' => 'api']);
        Role::create(['name' => 'admin', 'guard_name' => 'api']);
        Role::create(['name' => 'user', 'guard_name' => 'api']);
    }
}
