<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('equipment_types')->truncate();
        DB::table('equipment_types')->insert([
            ['name'=> 'PANEL SOLAR', 'pqr_type_id' => 2, 'description' => 'Fallas o resultados no deseados en la plataforma web o aplicativo movil'],
            ['name'=> 'CONTROLADOR', 'pqr_type_id' => 2, 'description' => 'Fallas o averias en los equipos de generacion o almacenamiento de energia electrica'],
            ['name'=> 'INVERSOR', 'pqr_type_id' => 2, 'description' => 'Fallas o averias en el equipo de medicion, tarjeta de control, o interfaz de usuario'],
            ['name'=> 'BATERIA', 'pqr_type_id' => 2, 'description' => 'Falla o averias en elementos de proteccion o contactor'],
            ['name'=> 'PRECINTO', 'pqr_type_id' => 6, 'description' => 'Fallas o averias en la linea de conexion electrica'],
            ['name'=> 'MEDIDOR ELECTRICO', 'pqr_type_id' => 3, 'description' => 'Fallas o averias en la linea de conexion electrica'],
            ['name'=> 'CONTACTOR', 'pqr_type_id' => 4, 'description' => 'Precinto de seguridad forzado'],
            ['name'=> 'TARJETA DE CONTROL', 'pqr_type_id' => 3, 'description' => 'Precinto de seguridad forzado'],
            ['name'=> 'PANTALLA', 'pqr_type_id' => 3, 'description' => 'Precinto de seguridad forzado'],
            ['name'=> 'TECLADO', 'pqr_type_id' => 3, 'description' => 'Precinto de seguridad forzado'],
            ['name'=> 'TARJETA PREPAGO', 'pqr_type_id' => 3, 'description' => 'Precinto de seguridad forzado'],
            ['name'=> 'LECTOR DE TARJETA', 'pqr_type_id' => 3, 'description' => 'Precinto de seguridad forzado'],
        ]);
    }
}
