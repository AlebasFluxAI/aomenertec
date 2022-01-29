<?php

namespace Database\Seeders;

use App\Models\V1\LocationType;
use Illuminate\Database\Seeder;

class LocationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LocationType::create([
            'type' => 'CABECERA MUNICIPAL',
            'acronym' => 'CM',
        ]);
        LocationType::create([
            'type' => 'CENTRO POBLADO',
            'acronym' => 'CP',
        ]);
        LocationType::create([
            'type' => 'VEREDA',
            'acronym' => 'VDA',
        ]);
        LocationType::create([
            'type' => 'RESGUARDO INDIGENA',
            'acronym' => 'RI',
        ]);
    }
}
