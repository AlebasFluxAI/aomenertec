<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('roles')->truncate();
        $roles = [
            ['name' => "super_administrator", 'display_name' => 'SUPER ADMINISTRADOR', 'guard_name' => 'web'],
            ['name' => "administrator", 'display_name' => 'ADMINISTRADOR', 'guard_name' => 'web'],
            ['name' => "support", 'display_name' => 'SOPORTE TÉCNICO', 'guard_name' => 'web'],
            ['name' => "network_operator", 'display_name' => 'OPERADOR DE RED', 'guard_name' => 'web'],
            ['name' => "seller", 'display_name' => 'VENDEDOR', 'guard_name' => 'web'],
            ['name' => "technician", 'display_name' => 'TÉCNICO', 'guard_name' => 'web'],
            ['name' => "supervisor", 'display_name' => 'SUPERVISOR', 'guard_name' => 'web']
        ];
        foreach ($roles as $role) {
            $create = Role::create($role);
        }
    }
}
