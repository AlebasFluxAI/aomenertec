<?php

namespace Database\Seeders;

use App\Models\V1\NetworkOperator;
use Illuminate\Database\Seeder;
use App\Models\V1\User;
use App\Models\V1\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        // DB::table('service_providers')->truncate();
        foreach ($providers as $item) {
            NetworkOperator::create([
                'user_id' => $item->id,
                'admin_id' => $admin->id,
            ]);
        }
    }
}
