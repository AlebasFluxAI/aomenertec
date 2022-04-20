<?php

namespace Database\Seeders;

use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Illuminate\Database\Seeder;
use App\Models\V1\Admin;
use Faker;

class SuperAdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::whereType(User::TYPE_SUPER_ADMIN)->get() as $user) {
            $user->superAdmin()->create([
                "name" => $user->name,
                "last_name" => $user->last_name,
                "identification" => $user->identification,
                "phone" => $user->phone,
                "email" => $user->email,
            ]);
        }
    }
}
