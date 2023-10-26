<?php

namespace Database\Seeders;

use App\Models\V1\Stratum;
use Illuminate\Database\Seeder;

class StrataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stratum::create([
            'acronym' => 'E1',
            'name' => 'Estrato uno',
        ]);
        Stratum::create([
            'acronym' => 'E2',
            'name' => 'Estrato dos',
        ]);
        Stratum::create([
            'acronym' => 'E3',
            'name' => 'Estrato tres',
        ]);
        Stratum::create([
            'acronym' => 'E4',
            'name' => 'Estrato cuatro',
        ]);
        Stratum::create([
            'acronym' => 'E5',
            'name' => 'Estrato cinco',
        ]);
        Stratum::create([
            'acronym' => 'E6',
            'name' => 'Estrato seis',
        ]);
        Stratum::create([
            'acronym' => 'COM',
            'name' => 'Comercial',
        ]);
        Stratum::create([
            'acronym' => 'IND',
            'name' => 'Industrial',
        ]);
        Stratum::create([
            'acronym' => 'EP',
            'name' => 'Entidades publicas',
        ]);
    }
}
