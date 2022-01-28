<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('equipment_conditions')->truncate();
        DB::table('equipment_conditions')->insert([
            ['condition'=> 'BUEN ESTADO', 'description' => 'Equipo funcionando en optimas condiciones'],
            ['condition'=> 'PRESENTA FALLAS', 'description' => 'Equipo con averias reparables'],
            ['condition'=> 'MAL ESTADO', 'description' => 'Equipo con averias Irreparables'],
        ]);
    }
}
