<?php

namespace Database\Seeders;

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
            /*ComponentesCUSeeder::class,
            SubsidioRCSeeder::class,
            TopologiaRedSeeder::class,
            NivelTensionSeeder::class,*/
            UsersTableSeeder::class,
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            ModelHasRoleTableSeeder::class,
            NetworkOperatorsTableSeeder::class,
            SellersTableSeeder::class,
            TechniciansTableSeeder::class,
            SupportsTableSeeder::class,
            ClientTypesTableSeeder::class,
            PqrTypesTableSeeder::class,
            EquipmentConditionsTableSeeder::class,
            EquipmentTypesTableSeeder::class,
            EquipmentAssignmentsTableSeeder::class,
            EquipmentsTableSeeder::class,
            StrataTableSeeder::class,
            SubsistenceConsumptionsTableSeeder::class,
            VoltageLevelsTableSeeder::class,
            LocationTypesTableSeeder::class,
            NetworkTopologiesTableSeeder::class,
            EquipmentAssignmentsTableSeeder::class,
            PqrStatesTableSeeder::class,
            RoleHasPermissionsTableSeeder::class,
        ]);
    }
}
