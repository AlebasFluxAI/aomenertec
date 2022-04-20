<?php

namespace Database\Seeders;

use App\Models\V1\PqrType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PqrTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('pqr_types')->truncate();
        $types = [
            ['type'=> 'FALLA EN PLATAFORMA', 'description' => 'Fallas o resultados no deseados en la plataforma web o aplicativo movil'],
            ['type'=> 'FALLA DE GENERACIÓN', 'description' => 'Fallas o averias en los equipos de generacion o almacenamiento de energia electrica'],
            ['type'=> 'FALLA EN MEDIDOR', 'description' => 'Fallas o averias en el equipo de medicion, tarjeta de control, o interfaz de usuario'],
            ['type'=> 'FALLA DE CONTACTOR', 'description' => 'Falla o averias en contactor'],
            ['type'=> 'FALLA EN ACOMETIDA ELECTRICA', 'description' => 'Fallas o averias en la linea de conexion electrica'],
            ['type'=> 'PRECINTO DE SEGURIDAD', 'description' => 'Precinto de seguridad forzado'],
        ];
        foreach ($types as $type) {
            $create = PqrType::create($type);
        }
    }
}
