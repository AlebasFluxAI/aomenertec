<?php

namespace Database\Seeders;

use App\Models\V1\User;
use Illuminate\Database\Seeder;
use App\Models\V1\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::find(1);
        $create = Admin::create([
            'user_id' => $admin->id
        ]);
    }
}
