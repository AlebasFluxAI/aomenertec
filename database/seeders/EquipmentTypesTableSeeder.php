<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\V1\EquipmentType;

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
        $types = [
            ['type'=> 'GABINETE', 'description' => 'Fallas o resultados no deseados en la plataforma web o aplicativo movil'],
            ['type'=> 'PANEL SOLAR', 'description' => 'Fallas o resultados no deseados en la plataforma web o aplicativo movil'],
            ['type'=> 'CONTROLADOR', 'description' => 'Fallas o averias en los equipos de generacion o almacenamiento de energia electrica'],
            ['type'=> 'INVERSOR', 'description' => 'Fallas o averias en el equipo de medicion, tarjeta de control, o interfaz de usuario'],
            ['type'=> 'BATERIA', 'description' => 'Falla o averias en elementos de proteccion o contactor'],
            ['type'=> 'PRECINTO', 'description' => 'Fallas o averias en la linea de conexion electrica'],
            ['type'=> 'MEDIDOR ELECTRICO', 'description' => 'Fallas o averias en la linea de conexion electrica'],
            ['type'=> 'CONTACTOR', 'description' => 'Precinto de seguridad forzado'],
            ['type'=> 'TARJETA DE CONTROL', 'description' => 'Precinto de seguridad forzado'],
            ['type'=> 'IBD', 'description' => 'Equipo para control de reactivos'],
            ['type'=> 'PANTALLA', 'description' => 'Precinto de seguridad forzado'],
            ['type'=> 'TECLADO', 'description' => 'Precinto de seguridad forzado'],
            ['type'=> 'TARJETA PREPAGO', 'description' => 'Precinto de seguridad forzado'],
            ['type'=> 'LECTOR DE TARJETA', 'description' => 'Precinto de seguridad forzado'],
        ];
        foreach ($types as $type) {
            $create = EquipmentType::create($type);
        }
    }
}
