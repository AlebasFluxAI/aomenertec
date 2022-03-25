<?php

namespace Database\Seeders;

use App\Models\V1\Client;
use App\Models\V1\EquipmentClient;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = Client::create([
            'code' => 1234567890,
            'identification' => 123456789,
            'name' => 'Cliente prueba',
            'email' => 'clienteprueba@gmail.com',
            'phone' => '000000',
            'network_operator_id' => 1,
        ]);
        EquipmentClient::create([
            'client_id' => $client->id,
            'equipment_id' => 1,
            'current_assigned' => true,
        ]);
        EquipmentClient::create([
            'client_id' => $client->id,
            'equipment_id' => 25,
            'current_assigned' => true,
        ]);
        EquipmentClient::create([
            'client_id' => $client->id,
            'equipment_id' => 31,
            'current_assigned' => true,
        ]);
        EquipmentClient::create([
            'client_id' => $client->id,
            'equipment_id' => 37,
            'current_assigned' => true,
        ]);
        EquipmentClient::create([
            'client_id' => $client->id,
            'equipment_id' => 43,
            'current_assigned' => true,
        ]);
    }
}
