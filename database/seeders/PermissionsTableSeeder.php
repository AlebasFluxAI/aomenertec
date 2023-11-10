<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('perimissions')->truncate();
        $permissions = [
            ['name' => "add_admin", 'guard_name' => 'web'],
            ['name' => "edit_admin", 'guard_name' => 'web'],
            ['name' => "delete_admin", 'guard_name' => 'web'],
            ['name' => "add_network_operator", 'guard_name' => 'web'],
            ['name' => "edit_network_operator", 'guard_name' => 'web'],
            ['name' => "delete_network_operator", 'guard_name' => 'web'],
            ['name' => "add_user", 'guard_name' => 'web'],
            ['name' => "edit_user", 'guard_name' => 'web'],
            ['name' => "delete_user", 'guard_name' => 'web'],
            ['name' => "add_client", 'guard_name' => 'web'],
            ['name' => "edit_client", 'guard_name' => 'web'],
            ['name' => "delete_client", 'guard_name' => 'web'],
        ];
        foreach ($permissions as $permission) {
            $create = Permission::create($permission);
        }
    }
}
