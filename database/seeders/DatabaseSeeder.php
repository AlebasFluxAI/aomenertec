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
        // Verificar si el seeder ya fue ejecutado (solo si la tabla existe)
        try {
            if (\Schema::hasTable('seeders') && SeederModel::where('name', $class)->exists()) {
                return $this;
            }
        } catch (\Exception $e) {
            // Si hay error verificando, continuar sin registrar
        }

        $return = parent::call($class, $silent = false);

        // Registrar el seeder ejecutado (solo si la tabla existe y sin timestamps automáticos)
        try {
            if (\Schema::hasTable('seeders')) {
                \DB::table('seeders')->insert([
                    'name' => is_array($class) ? $class[0] : $class,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Silenciar errores al registrar seeders
        }

        return $return;
    }
}
