<?php

namespace Database\Seeders;

use App\Models\V1\Api\ApiKey;
use App\Models\V1\User;
use Illuminate\Database\Seeder;

class ApiKeySeeder extends Seeder
{
    /**
     * Seed a development API key for inter-service communication testing.
     *
     * This key is used in the x-api-key header to authenticate requests
     * between aomenertec and aomenertec-api.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::where('email', 'sadminprueba@fluxai.local')->first();

        ApiKey::updateOrCreate(
            ['api_key' => 'dev-api-key-enertec-2026'],
            [
                'user_id' => $superAdmin ? $superAdmin->id : 1,
                'status' => ApiKey::STATUS_ENABLED,
                'expiration' => '2030-12-31 23:59:59',
                'security_header_key' => 'X-API-Key',
                'security_header_value' => 'dev-api-key-enertec-2026',
            ]
        );
    }
}
