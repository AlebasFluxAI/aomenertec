<?php

namespace Database\Seeders;

use App\Models\V1\Seller;
use App\Models\V1\User;
use Illuminate\Database\Seeder;

class SellersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seller = User::where("name", "like", '%' . "Vendedor" . "%")->get();
        $i = 1;
        foreach ($seller as $item) {
            Seller::create([
                'user_id' => $item->id,
                'network_operator_id' => $i,
            ]);
            $i++;
        }
    }
}
