<?php

namespace Database\Seeders;

use App\Models\V1\VoltageLevel;
use Illuminate\Database\Seeder;

class VoltageLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VoltageLevel::create([
            'level' => 'NIVEL 1(a)',
            'description' => 'Sistemas con tension nominal menor a 1kV (PROPIEDAD: EMPRESA)',
        ]);
        VoltageLevel::create([
            'level' => 'NIVEL 1(b)',
            'description' => 'Sistemas con tension nominal menor a 1kV (PROPIEDAD: COMPARTIDA)',
        ]);
        VoltageLevel::create([
            'level' => 'NIVEL 1(c)',
            'description' => 'Sistemas con tension nominal menor a 1kV (PROPIEDAD: CLIENTE)',
        ]);
        VoltageLevel::create([
            'level' => 'NIVEL 2',
            'description' => 'Sistemas con tension nominal entre 1kV y 30kV',
        ]);
        VoltageLevel::create([
            'level' => 'NIVEL 3',
            'description' => 'Sistemas con tension nominal entre 30kV y 57.5kV',
        ]);
        VoltageLevel::create([
            'level' => 'NIVEL 4',
            'description' => 'Sistemas con tension nominal mayor a 57.5kV',
        ]);
    }
}
