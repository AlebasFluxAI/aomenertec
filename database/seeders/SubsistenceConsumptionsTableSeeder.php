<?php

namespace Database\Seeders;

use App\Models\V1\SubsistenceConsumption;
use Illuminate\Database\Seeder;

class SubsistenceConsumptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubsistenceConsumption::create([
            'value' => 173,
            'description' => 'Para clientes ubicados en municipios de clima calido (por debajo de 1.000 MSNM)',
        ]);
        SubsistenceConsumption::create([
            'value' => 130,
            'description' => 'Para usuarios ubicados en municipios de climas templados y fríos (por encima de 1.000 MSNM))',
        ]);
    }
}
