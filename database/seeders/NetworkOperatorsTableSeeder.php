<?php

namespace Database\Seeders;

use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Illuminate\Database\Seeder;

class NetworkOperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $providers = User::where("name", "like", '%' . "Prestador" . "%")->get();
        $admin = Admin::find(1);
        foreach ($providers as $item) {
            NetworkOperator::create([
                'user_id' => $item->id,
                'admin_id' => $admin->id,
            ]);
        }
    }
}
