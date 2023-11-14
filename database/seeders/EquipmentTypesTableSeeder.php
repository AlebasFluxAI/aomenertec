<?php

namespace Database\Seeders;

use App\Models\V1\EquipmentType;
use Illuminate\Database\Seeder;

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
            ['serialized' => true, 'type' => 'GABINETE', 'description' => 'Fallas o resultados no deseados en la plataforma web o aplicativo movil'],
            ['serialized' => true, 'type' => 'PANEL SOLAR', 'description' => 'Fallas o resultados no deseados en la plataforma web o aplicativo movil'],
            ['serialized' => true, 'type' => 'CONTROLADOR', 'description' => 'Fallas o averias en los equipos de generacion o almacenamiento de energia electrica'],
            ['serialized' => true, 'type' => 'INVERSOR', 'description' => 'Fallas o averias en el equipo de medicion, tarjeta de control, o interfaz de usuario'],
            ['serialized' => true, 'type' => 'BATERIA', 'description' => 'Falla o averias en elementos de proteccion o contactor'],
            ['serialized' => true, 'type' => 'PRECINTO GABINETE', 'description' => 'Fallas o averias en la linea de conexion electrica'],
            ['serialized' => true, 'type' => 'MEDIDOR ELECTRICO', 'description' => 'Fallas o averias en la linea de conexion electrica'],
            ['serialized' => true, 'type' => 'CONTACTOR', 'description' => 'Precinto de seguridad forzado'],
            ['serialized' => true, 'type' => 'TARJETA DE CONTROL', 'description' => 'Precinto de seguridad forzado'],
            ['serialized' => true, 'type' => 'IBD', 'description' => 'Equipo para control de reactivos'],
            ['serialized' => true, 'type' => 'PRECINTO MEDIDOR', 'description' => 'Precinto de seguridas medidor'],
            ['type' => 'PANTALLA', 'description' => 'Precinto de seguridad forzado'],
            ['type' => 'TECLADO', 'description' => 'Precinto de seguridad forzado'],
            ['type' => 'TARJETA PREPAGO', 'description' => 'Precinto de seguridad forzado'],
            ['type' => 'LECTOR DE TARJETA', 'description' => 'Precinto de seguridad forzado'],
        ];
        foreach ($types as $type) {
            $create = EquipmentType::create($type);
        }
    }
}
