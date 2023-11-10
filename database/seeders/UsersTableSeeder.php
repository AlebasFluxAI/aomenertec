<?php

namespace Database\Seeders;

use App\Models\V1\User;
use Illuminate\Database\Seeder;
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
        User::create([
            'name' => "Super Administrador",
            "last_name" => "Prueba",
            'identification' => '111111111',
            'phone' => '30111111111',
            'email' => 'sadminprueba@enerteclatam.com',
            'password' => bcrypt('12345678'),
            'remember_token' => Str::random(60),
            'type' => User::TYPE_SUPER_ADMIN
        ]);

        /*$users = [
            [
                'name' => "Super Administrador",
                "last_name" => "Prueba",
                'identification' => '111111111',
                'phone' => '30111111111',
                'email' => 'sadminprueba@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_SUPER_ADMIN
            ],
             [
                'name' => "Administrador Prueba",
                "last_name" => "Apellido",
                'identification' => '666666662',
                'phone' => '30666666662',
                'email' => 'adminprueba@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_ADMIN
            ],
            [
                'name' => "Edgar Sneider Fuentes Agudelo",
                "last_name" => "Apellido",
                'identification' => '11219345723',
                'phone' => '31033436163',
                'email' => 's.fuentes@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_ADMIN
            ],
            [
                'name' => "Prestador de servicio Prueba",
                "last_name" => "Apellido",
                'identification' => '999999994',
                'phone' => '3999999994',
                'email' => 'patrocinadorprueba@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_NETWORK_OPERATOR

            ],
            [
                'name' => "Prestador de servicio SFVI Prueba",
                "last_name" => "Apellido",
                'identification' => '888888885',
                'phone' => '3888888885',
                'email' => 'patrocinadorpruebaSFVI@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_NETWORK_OPERATOR

            ],
            [
                'name' => "Prestador de servicio RED Prueba",
                "last_name" => "Apellido",
                'identification' => '777777776',
                'phone' => '3777777776',
                'email' => 'patrocinadorpruebaRED@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_NETWORK_OPERATOR

            ],
            [
                'name' => "Vendedor Prueba",
                "last_name" => "Apellido",
                'identification' => '999999117',
                'phone' => '3999999117',
                'email' => 'vendedorprueba@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_SELLER

            ],
            [
                'name' => "Vendedor Prueba SFVI",
                "last_name" => "Apellido",
                'identification' => '888888118',
                'phone' => '3888888118',
                'email' => 'vendedorpruebaSFVI@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_SELLER

            ],
            [
                'name' => "Vendedor Prueba RED",
                "last_name" => "Apellido",
                'identification' => '777777119',
                'phone' => '3777777119',
                'email' => 'vendedorpruebaRED@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_SELLER

            ],
            [
                'name' => "Tecnico Prueba",
                "last_name" => "Apellido",
                'identification' => '2299999910',
                'phone' => '32299999910',
                'email' => 'tecnicoprueba@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_TECHNICIAN

            ],
            [
                'name' => "Tecnico Prueba SFVI",
                "last_name" => "Apellido",
                'identification' => '2288888811',
                'phone' => '32288888811',
                'email' => 'tecnicopruebaSFVI@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_TECHNICIAN

            ],
            [
                'name' => "Tecnico Prueba RED",
                "last_name" => "Apellido",
                'identification' => '2277777712',
                'phone' => '32277777712',
                'email' => 'tecnicopruebaRED@enerteclatam.com',
                'password' => bcrypt('12345678'),
                'remember_token' => Str::random(60),
                'type' => User::TYPE_TECHNICIAN

            ]

        ];
        foreach ($users as $user) {
            $create = User::create($user);
        }*/
    }
}
