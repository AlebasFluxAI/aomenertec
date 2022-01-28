<?php

namespace Database\Seeders;

use App\Models\v1\PqrState;
use Illuminate\Database\Seeder;

class PqrStatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PqrState::create([
            'state' => 'CREADO',
            'description' => 'PQR creado sin ser atendido',
        ]);
        PqrState::create([
            'state' => 'EN TRAMITE',
            'description' => 'PQR recibido y atendido',
        ]);
        PqrState::create([
            'state' => 'RESUELTO',
            'description' => 'PQR solucionado y diagnosticado, a la espera confirmacion',
        ]);
        PqrState::create([
            'state' => 'CERRADO',
            'description' => 'PQR con diagnostico y solucion aceptados',
        ]);
    }
}
