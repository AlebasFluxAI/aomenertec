<?php

namespace Database\Seeders;

use App\Models\V1\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleHasPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::findByName("super_administrator");
        $permissions = Permission::all();
        $role->syncPermissions($permissions);
    }
}
