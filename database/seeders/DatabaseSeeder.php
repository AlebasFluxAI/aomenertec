<?php

namespace Database\Seeders;

use App\Models\V1\Seeder as SeederModel;
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
            RolesTableSeeder::class,//
            UsersTableSeeder::class,
            PermissionsTableSeeder::class,
            SuperAdminsTableSeeder::class,
            AdminsTableSeeder::class,
            NetworkOperatorsTableSeeder::class,
            SellersTableSeeder::class,
            TechniciansTableSeeder::class,
            SupportsTableSeeder::class,
            ClientTypesTableSeeder::class,//
            EquipmentTypesTableSeeder::class,//
            ClientTypeEquipmentTypesTableSeeder::class,
            EquipmentsTableSeeder::class,
            StrataTableSeeder::class,//
            SubsistenceConsumptionsTableSeeder::class,//
            VoltageLevelsTableSeeder::class,//
            RoleHasPermissionsTableSeeder::class,
            ClientsTableSeeder::class,
        ]);
    }

    public function call($class, $silent = false, $parameters = [])
    {
        if (SeederModel::where('name', $class)->exists()) {
            return $this;
        }

        $return = parent::call($class, $silent = false);

        SeederModel::create(['name' => $class]);

        return $return;
    }
}
