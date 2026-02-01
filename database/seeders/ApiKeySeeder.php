<?php

namespace Database\Seeders;

use App\Models\V1\Api\ApiKey;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = \App\Models\V1\User::where('email', 'sadminprueba@enerteclatam.com')->first();
        
        ApiKey::create([
            'api_key' => 'enertec-local-' . Str::random(32),
            'status' => 'enabled',
            'security_header_key' => 'X-API-Key',
            'security_header_value' => Str::random(32),
            'expiration' => now()->addYears(10), // Expira en 10 años
            'user_id' => $superAdmin ? $superAdmin->id : 1,
        ]);
    }
}
