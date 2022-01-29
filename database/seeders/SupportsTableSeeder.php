<?php

namespace Database\Seeders;

use App\Models\V1\Support;
use App\Models\V1\User;
use Illuminate\Database\Seeder;

class SupportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $support = User::find(2);
        Support::create([
            'user_id' => $support->id,
        ]);
    }
}
