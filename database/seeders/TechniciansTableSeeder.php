<?php

namespace Database\Seeders;

use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Database\Seeder;

class TechniciansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $technician = User::where("name", "like", '%' . "Tecnico" . "%")->get();
        $i = 1;
        foreach ($technician as $item) {
            Technician::create([
                'user_id' => $item->id,
                'network_operator_id' => $i,
            ]);
            $i++;
        }
    }
}
