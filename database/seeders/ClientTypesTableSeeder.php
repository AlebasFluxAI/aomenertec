<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('client_types')->truncate();
        DB::table('client_types')->insert([
            ['type'=> 'SFVI', 'description' => 'Cliente con solucion fotovoltaica individual sin medición'],
            ['type'=> 'SFVI PREPAGO', 'description' => 'Cliente con solucion fotovoltaica individual con medición prepago'],
            ['type'=> 'SFVI PREPAGO-TELEMETRIA', 'description' => 'Cliente con solucion fotovoltaica individual con medición prepago y telemetria'],
            ['type'=> 'SFVI POSTPAGO', 'description' => 'Cliente con solucion fotovoltaica individual con medición postpago'],
            ['type'=> 'SFVI POSTPAGO-TELEMETRIA', 'description' => 'Cliente con solucion fotovoltaica individual con medición postpago y telemetria'],
            ['type'=> 'CONVENCIONAL PREPAGO', 'description' => 'Cliente con red convencional con medición prepago'],
            ['type'=> 'CONVENCIONAL PREPAGO-TELEMETRIA', 'description' => 'Cliente con red convencional con medición prepago y telemetria'],
            ['type'=> 'CONVENCIONAL POSTPAGO', 'description' => 'Cliente con red convencional con medición postpago'],
            ['type'=> 'CONVENCIONAL POSTPAGO-TELEMETRIA', 'description' => 'Cliente con red convencional con medición postpago y telemetria'],
            ]);
    }
}
