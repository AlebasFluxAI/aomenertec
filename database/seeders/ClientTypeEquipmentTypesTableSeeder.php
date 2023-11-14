<?php

namespace Database\Seeders;

use App\Models\V1\ClientTypeEquipmentTypes;
use Illuminate\Database\Seeder;

class ClientTypeEquipmentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('equipment_assignments')->truncate();
        $types = [
            ['equipment_type_id' => 1, 'client_type_id' => 1],
            ['equipment_type_id' => 2, 'client_type_id' => 1],
            ['equipment_type_id' => 3, 'client_type_id' => 1],
            ['equipment_type_id' => 4, 'client_type_id' => 1],
            ['equipment_type_id' => 5, 'client_type_id' => 1],
            ['equipment_type_id' => 6, 'client_type_id' => 1],
            ['equipment_type_id' => 1, 'client_type_id' => 2],
            ['equipment_type_id' => 2, 'client_type_id' => 2],
            ['equipment_type_id' => 3, 'client_type_id' => 2],
            ['equipment_type_id' => 4, 'client_type_id' => 2],
            ['equipment_type_id' => 5, 'client_type_id' => 2],
            ['equipment_type_id' => 6, 'client_type_id' => 2],
            ['equipment_type_id' => 7, 'client_type_id' => 2],
            ['equipment_type_id' => 9, 'client_type_id' => 2],
            ['equipment_type_id' => 1, 'client_type_id' => 3],
            ['equipment_type_id' => 2, 'client_type_id' => 3],
            ['equipment_type_id' => 3, 'client_type_id' => 3],
            ['equipment_type_id' => 4, 'client_type_id' => 3],
            ['equipment_type_id' => 5, 'client_type_id' => 3],
            ['equipment_type_id' => 6, 'client_type_id' => 3],
            ['equipment_type_id' => 7, 'client_type_id' => 3],
            ['equipment_type_id' => 8, 'client_type_id' => 3],
            ['equipment_type_id' => 9, 'client_type_id' => 3],
            ['equipment_type_id' => 1, 'client_type_id' => 4],
            ['equipment_type_id' => 2, 'client_type_id' => 4],
            ['equipment_type_id' => 3, 'client_type_id' => 4],
            ['equipment_type_id' => 4, 'client_type_id' => 4],
            ['equipment_type_id' => 5, 'client_type_id' => 4],
            ['equipment_type_id' => 6, 'client_type_id' => 4],
            ['equipment_type_id' => 7, 'client_type_id' => 4],
            ['equipment_type_id' => 8, 'client_type_id' => 4],
            ['equipment_type_id' => 9, 'client_type_id' => 4],
            ['equipment_type_id' => 1, 'client_type_id' => 5],
            ['equipment_type_id' => 2, 'client_type_id' => 5],
            ['equipment_type_id' => 3, 'client_type_id' => 5],
            ['equipment_type_id' => 4, 'client_type_id' => 5],
            ['equipment_type_id' => 5, 'client_type_id' => 5],
            ['equipment_type_id' => 6, 'client_type_id' => 5],
            ['equipment_type_id' => 7, 'client_type_id' => 5],
            ['equipment_type_id' => 8, 'client_type_id' => 5],
            ['equipment_type_id' => 9, 'client_type_id' => 5],
            ['equipment_type_id' => 1, 'client_type_id' => 6],
            ['equipment_type_id' => 6, 'client_type_id' => 6],
            ['equipment_type_id' => 7, 'client_type_id' => 6],
            ['equipment_type_id' => 8, 'client_type_id' => 6],
            ['equipment_type_id' => 9, 'client_type_id' => 6],
            ['equipment_type_id' => 1, 'client_type_id' => 7],
            ['equipment_type_id' => 6, 'client_type_id' => 7],
            ['equipment_type_id' => 7, 'client_type_id' => 7],
            ['equipment_type_id' => 8, 'client_type_id' => 7],
            ['equipment_type_id' => 9, 'client_type_id' => 7],
            ['equipment_type_id' => 1, 'client_type_id' => 8],
            ['equipment_type_id' => 6, 'client_type_id' => 8],
            ['equipment_type_id' => 7, 'client_type_id' => 8],
            ['equipment_type_id' => 8, 'client_type_id' => 8],
            ['equipment_type_id' => 9, 'client_type_id' => 8],
            ['equipment_type_id' => 1, 'client_type_id' => 9],
            ['equipment_type_id' => 6, 'client_type_id' => 9],
            ['equipment_type_id' => 7, 'client_type_id' => 9],
            ['equipment_type_id' => 8, 'client_type_id' => 9],
            ['equipment_type_id' => 9, 'client_type_id' => 9],
        ];
        foreach ($types as $type) {
            $create = ClientTypeEquipmentTypes::create($type);
        }
    }
}
