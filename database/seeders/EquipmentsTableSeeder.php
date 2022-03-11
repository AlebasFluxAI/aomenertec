<?php

namespace Database\Seeders;

use App\Models\V1\Equipment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('equipments')->truncate();
        $equipment = [
            ['equipment_type_id'=> 1, 'serial' => '999999', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id'=> 1, 'serial' => '999998', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id'=> 1, 'serial' => '999997', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id'=> 1, 'serial' => '999996', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id'=> 1, 'serial' => '999995', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id'=> 1, 'serial' => '999994', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id'=> 2, 'serial' => '999999', 'description' => '24V-60A'],
            ['equipment_type_id'=> 2, 'serial' => '999998', 'description' => '24V-60A'],
            ['equipment_type_id'=> 2, 'serial' => '999997', 'description' => '24V-60A'],
            ['equipment_type_id'=> 3, 'serial' => '999999', 'description' => '24V-1000W'],
            ['equipment_type_id'=> 3, 'serial' => '999998', 'description' => '24V-1000W'],
            ['equipment_type_id'=> 3, 'serial' => '999997', 'description' => '24V-1000W'],
            ['equipment_type_id'=> 4, 'serial' => '999999', 'description' => '12V-150Ah'],
            ['equipment_type_id'=> 4, 'serial' => '999998', 'description' => '12V-150Ah'],
            ['equipment_type_id'=> 4, 'serial' => '999997', 'description' => '12V-150Ah'],
            ['equipment_type_id'=> 4, 'serial' => '999996', 'description' => '12V-150Ah'],
            ['equipment_type_id'=> 4, 'serial' => '999995', 'description' => '12V-150Ah'],
            ['equipment_type_id'=> 4, 'serial' => '999994', 'description' => '12V-150Ah'],
            ['equipment_type_id'=> 5, 'serial' => '999999', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id'=> 5, 'serial' => '999998', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id'=> 5, 'serial' => '999997', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id'=> 5, 'serial' => '999996', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id'=> 5, 'serial' => '999995', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id'=> 5, 'serial' => '999994', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id'=> 6, 'serial' => '999999', 'description' => 'SMD230'],
            ['equipment_type_id'=> 6, 'serial' => '999998', 'description' => 'SMD230'],
            ['equipment_type_id'=> 6, 'serial' => '999997', 'description' => 'SMD230'],
            ['equipment_type_id'=> 6, 'serial' => '999996', 'description' => 'SMD230'],
            ['equipment_type_id'=> 6, 'serial' => '999995', 'description' => 'SMD230'],
            ['equipment_type_id'=> 6, 'serial' => '999994', 'description' => 'SMD230'],
            ['equipment_type_id'=> 7, 'serial' => '999999', 'description' => '120V-20A'],
            ['equipment_type_id'=> 7, 'serial' => '999998', 'description' => '120V-20A'],
            ['equipment_type_id'=> 7, 'serial' => '999997', 'description' => '120V-20A'],
            ['equipment_type_id'=> 7, 'serial' => '999996', 'description' => '120V-20A'],
            ['equipment_type_id'=> 7, 'serial' => '999995', 'description' => '120V-20A'],
            ['equipment_type_id'=> 7, 'serial' => '999994', 'description' => '120V-20A'],
            ['equipment_type_id'=> 7, 'serial' => '999999', 'description' => 'TARJETA DE CONTROL GP'],
            ['equipment_type_id'=> 7, 'serial' => '999998', 'description' => 'TARJETA DE CONTROL GP'],
            ['equipment_type_id'=> 7, 'serial' => '999997', 'description' => 'TARJETA DE CONTROL GP'],
            ['equipment_type_id'=> 7, 'serial' => '999996', 'description' => 'TARJETA DE CONTROL GP'],
            ['equipment_type_id'=> 7, 'serial' => '999995', 'description' => 'TARJETA DE CONTROL GP'],
            ['equipment_type_id'=> 7, 'serial' => '999994', 'description' => 'TARJETA DE CONTROL GP'],
        ];
        foreach ($equipment as $item){
            $create = Equipment::create($item);
        }
    }
}
