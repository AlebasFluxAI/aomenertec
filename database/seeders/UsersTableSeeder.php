<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'name' => "ENERTEC LATINOAMERICA SAS",
                'identification' => '901091737',
                'phone' => '3058139238',
                'email' => 'soporte@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],
            [
                'name' => "Edgar Sneider Fuentes Agudelo",
                'identification' => '1121934572',
                'phone' => '3103343616',
                'email' => 's.fuentes@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],
            [
                'name' => "Prestador de servicio Prueba",
                'identification' => '99999999',
                'phone' => '399999999',
                'email' => 'patrocinadorprueba@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],
            [
                'name' => "Prestador de servicio SFVI Prueba",
                'identification' => '88888888',
                'phone' => '388888888',
                'email' => 'patrocinadorpruebaSFVI@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],
            [
                'name' => "Prestador de servicio RED Prueba",
                'identification' => '77777777',
                'phone' => '377777777',
                'email' => 'patrocinadorpruebaRED@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],
            [
                'name' => "Vendedor Prueba",
                'identification' => '99999911',
                'phone' => '399999911',
                'email' => 'vendedorprueba@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],
            [
                'name' => "Vendedor Prueba SFVI",
                'identification' => '88888811',
                'phone' => '388888811',
                'email' => 'vendedorpruebaSFVI@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],
            [
                'name' => "Vendedor Prueba RED",
                'identification' => '77777711',
                'phone' => '377777711',
                'email' => 'vendedorpruebaRED@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],
            [
                'name' => "Tecnico Prueba",
                'identification' => '22999999',
                'phone' => '322999999',
                'email' => 'tecnicoprueba@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],
[
                'name' => "Tecnico Prueba SFVI",
                'identification' => '22888888',
                'phone' => '322888888',
                'email' => 'tecnicopruebaSFVI@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],
[
                'name' => "Tecnico Prueba RED",
                'identification' => '22777777',
                'phone' => '322777777',
                'email' => 'tecnicopruebaRED@enerteclatam.com',
                'password'       => bcrypt('12345678'),
                'remember_token' => Str::random(60),
            ],

        ]);
    }
}
