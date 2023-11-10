<?php

namespace Database\Seeders;

use App\Models\V1\Equipment;
use Illuminate\Database\Seeder;

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
            ['equipment_type_id' => 1, 'name' => 'test', 'serial' => '999999', 'description' => 'GABINETE MEDIDOR MONOFASICO', 'assigned' => true],
            ['equipment_type_id' => 1, 'name' => 'test', 'serial' => '999998', 'description' => 'GABINETE MEDIDOR MONOFASICO'],
            ['equipment_type_id' => 1, 'name' => 'test', 'serial' => '999997', 'description' => 'GABINETE MEDIDOR MONOFASICO'],
            ['equipment_type_id' => 1, 'name' => 'test', 'serial' => '999996', 'description' => 'GABINETE MEDIDOR MONOFASICO'],
            ['equipment_type_id' => 1, 'name' => 'test', 'serial' => '999995', 'description' => 'GABINETE MEDIDOR MONOFASICO'],
            ['equipment_type_id' => 1, 'name' => 'test', 'serial' => '999994', 'description' => 'GABINETE MEDIDOR MONOFASICO'],
            ['equipment_type_id' => 2, 'name' => 'test', 'serial' => '999999', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id' => 2, 'name' => 'test', 'serial' => '999998', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id' => 2, 'name' => 'test', 'serial' => '999997', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id' => 2, 'name' => 'test', 'serial' => '999996', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id' => 2, 'name' => 'test', 'serial' => '999995', 'description' => 'MONOPERC 380W'],
            ['equipment_type_id' => 2, 'name' => 'test', 'serial' => '999994', 'description' => 'MONOPERC 380W'],

            ['equipment_type_id' => 3, 'name' => 'test', 'serial' => '999999', 'description' => '24V-60A'],
            ['equipment_type_id' => 3, 'name' => 'test', 'serial' => '999998', 'description' => '24V-60A'],
            ['equipment_type_id' => 3, 'name' => 'test', 'serial' => '999997', 'description' => '24V-60A'],
            ['equipment_type_id' => 4, 'name' => 'test', 'serial' => '999999', 'description' => '24V-1000W', 'assigned' => true],
            ['equipment_type_id' => 4, 'name' => 'test', 'serial' => '999998', 'description' => '24V-1000W'],
            ['equipment_type_id' => 4, 'name' => 'test', 'serial' => '999997', 'description' => '24V-1000W'],
            ['equipment_type_id' => 5, 'name' => 'test', 'serial' => '999999', 'description' => '12V-150Ah'],
            ['equipment_type_id' => 5, 'name' => 'test', 'serial' => '999998', 'description' => '12V-150Ah'],
            ['equipment_type_id' => 5, 'name' => 'test', 'serial' => '999997', 'description' => '12V-150Ah'],
            ['equipment_type_id' => 5, 'name' => 'test', 'serial' => '999996', 'description' => '12V-150Ah'],
            ['equipment_type_id' => 5, 'name' => 'test', 'serial' => '999995', 'description' => '12V-150Ah'],
            ['equipment_type_id' => 5, 'name' => 'test', 'serial' => '999994', 'description' => '12V-150Ah'],
            ['equipment_type_id' => 6, 'name' => 'test', 'serial' => '999999', 'description' => 'PRECINTO DE APERTURA', 'assigned' => true],
            ['equipment_type_id' => 6, 'name' => 'test', 'serial' => '999998', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id' => 6, 'name' => 'test', 'serial' => '999997', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id' => 6, 'name' => 'test', 'serial' => '999996', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id' => 6, 'name' => 'test', 'serial' => '999995', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id' => 6, 'name' => 'test', 'serial' => '999994', 'description' => 'PRECINTO DE APERTURA'],
            ['equipment_type_id' => 7, 'name' => 'test', 'serial' => '999999', 'description' => 'SMD230', 'assigned' => true],
            ['equipment_type_id' => 7, 'name' => 'test', 'serial' => '999998', 'description' => 'SMD230'],
            ['equipment_type_id' => 7, 'name' => 'test', 'serial' => '999997', 'description' => 'SMD230'],
            ['equipment_type_id' => 7, 'name' => 'test', 'serial' => '999996', 'description' => 'SMD230'],
            ['equipment_type_id' => 7, 'name' => 'test', 'serial' => '999995', 'description' => 'SMD230'],
            ['equipment_type_id' => 7, 'name' => 'test', 'serial' => '999994', 'description' => 'SMD230'],
            ['equipment_type_id' => 8, 'name' => 'test', 'serial' => '999999', 'description' => '120V-20A'],
            ['equipment_type_id' => 8, 'name' => 'test', 'serial' => '999998', 'description' => '120V-20A'],
            ['equipment_type_id' => 8, 'name' => 'test', 'serial' => '999997', 'description' => '120V-20A'],
            ['equipment_type_id' => 8, 'name' => 'test', 'serial' => '999996', 'description' => '120V-20A'],
            ['equipment_type_id' => 8, 'name' => 'test', 'serial' => '999995', 'description' => '120V-20A'],
            ['equipment_type_id' => 8, 'name' => 'test', 'serial' => '999994', 'description' => '120V-20A'],
            ['equipment_type_id' => 9, 'name' => 'test', 'serial' => '999999', 'description' => 'TARJETA DE CONTROL GP', 'assigned' => true],
            ['equipment_type_id' => 9, 'name' => 'test', 'serial' => '999998', 'description' => 'TARJETA DE CONTROL GP'],
            ['equipment_type_id' => 9, 'name' => 'test', 'serial' => '999997', 'description' => 'TARJETA DE CONTROL GP'],
            ['equipment_type_id' => 9, 'name' => 'test', 'serial' => '999996', 'description' => 'TARJETA DE CONTROL GP'],
            ['equipment_type_id' => 9, 'name' => 'test', 'serial' => '999995', 'description' => 'TARJETA DE CONTROL GP'],
            ['equipment_type_id' => 9, 'name' => 'test', 'serial' => '999994', 'description' => 'TARJETA DE CONTROL GP'],
            ['equipment_type_id' => 10, 'name' => 'test', 'serial' => '999999', 'description' => 'EQUIPO COENERGIA'],
            ['equipment_type_id' => 10, 'name' => 'test', 'serial' => '999998', 'description' => 'EQUIPO COENERGIA'],
            ['equipment_type_id' => 10, 'name' => 'test', 'serial' => '999997', 'description' => 'EQUIPO COENERGIA'],
            ['equipment_type_id' => 10, 'name' => 'test', 'serial' => '999996', 'description' => 'EQUIPO COENERGIA'],
            ['equipment_type_id' => 10, 'name' => 'test', 'serial' => '999995', 'description' => 'EQUIPO COENERGIA'],
            ['equipment_type_id' => 10, 'name' => 'test', 'serial' => '999994', 'description' => 'EQUIPO COENERGIA'],
            ['equipment_type_id' => 11, 'name' => 'test', 'serial' => '999999', 'description' => 'PRECINTO MEDIDOR ELECTRICO'],
            ['equipment_type_id' => 11, 'name' => 'test', 'serial' => '999998', 'description' => 'PRECINTO MEDIDOR ELECTRICO'],
            ['equipment_type_id' => 11, 'name' => 'test', 'serial' => '999997', 'description' => 'PRECINTO MEDIDOR ELECTRICO'],
            ['equipment_type_id' => 11, 'name' => 'test', 'serial' => '999996', 'description' => 'PRECINTO MEDIDOR ELECTRICO'],
            ['equipment_type_id' => 11, 'name' => 'test', 'serial' => '999995', 'description' => 'PRECINTO MEDIDOR ELECTRICO'],
            ['equipment_type_id' => 11, 'name' => 'test', 'serial' => '999994', 'description' => 'PRECINTO MEDIDOR ELECTRICO'],

        ];

        foreach ($equipment as $item) {
            $create = Equipment::create($item);
        }
    }
}
