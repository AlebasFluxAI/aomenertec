<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::table('permissions')->insert([
            ['name' => "add_user", 'guard_name' => 'web'],
            ['name' => "edit_user", 'guard_name' => 'web'],
            ['name' => "delete_user", 'guard_name' => 'web'],
            ['name' => "add_client", 'guard_name' => 'web'],
            ['name' => "edit_client", 'guard_name' => 'web'],
            ['name' => "delete_client", 'guard_name' => 'web'],
        ]);
    }
}
