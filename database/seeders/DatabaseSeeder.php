<?php

namespace Database\Seeders;

use App\Models\V1\ClientTypeEquipmentTypes;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([

            UsersTableSeeder::class,
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            ModelHasRoleTableSeeder::class,
            AdminsTableSeeder::class,
            NetworkOperatorsTableSeeder::class,
            SellersTableSeeder::class,
            TechniciansTableSeeder::class,
            SupportsTableSeeder::class,
            ClientTypesTableSeeder::class,
            PqrTypesTableSeeder::class,
            EquipmentTypesTableSeeder::class,
            ClientTypeEquipmentTypesTableSeeder::class,
            EquipmentsTableSeeder::class,
            StrataTableSeeder::class,
            SubsistenceConsumptionsTableSeeder::class,
            VoltageLevelsTableSeeder::class,
            LocationTypesTableSeeder::class,
            RoleHasPermissionsTableSeeder::class,
            //ClientsTableSeeder::class,
        ]);
    }
}
