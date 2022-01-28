<?php

namespace Database\Seeders;

use App\Models\v1\NetworkTopology;
use Illuminate\Database\Seeder;

class NetworkTopologiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NetworkTopology::create([
            'topology' => 'MONOFÁSICA',
            'description' => 'Red eléctrica de una fase y 3 lineas (F-N-T)'
        ]);
        NetworkTopology::create([
            'topology' => 'BIFÁSICA',
            'description' => 'Red eléctrica de 2 fases y 4 lineas (2F-N-T)'
        ]);
        NetworkTopology::create([
            'topology' => 'TRIFÁSICA',
            'description' => 'Red eléctrica de tres fases y 5 lineas (3F-N-T)'
        ]);
    }
}
