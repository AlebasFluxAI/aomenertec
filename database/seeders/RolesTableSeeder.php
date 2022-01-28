<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::table('roles')->insert([
            ['name' => "administrator", 'display_name' => 'ADMINISTRADOR', 'guard_name' => 'web'],
            ['name' => "support", 'display_name' => 'SOPORTE TÉCNICO', 'guard_name' => 'web'],
            ['name' => "network_operator", 'display_name' => 'OPERADOR DE RED', 'guard_name' => 'web'],
            ['name' => "seller", 'display_name' => 'VENDEDOR', 'guard_name' => 'web'],
            ['name' => "technician", 'display_name' => 'TÉCNICO', 'guard_name' => 'web'],
            ['name' => "consumer", 'display_name' => 'CONSUMIDOR', 'guard_name' => 'web'],
            ['name' => "independent", 'display_name' => 'INDEPENDIENTE', 'guard_name' => 'web'],
        ]);
    }
}
