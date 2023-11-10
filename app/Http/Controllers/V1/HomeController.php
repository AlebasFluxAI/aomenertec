<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\Client;

class HomeController extends Controller
{
    public function index()
    {
        return redirect('/v1/inicio');
    }

    public function healthCheck()
    {
        try {
            $client = Client::first();
            $client->update([
                "updated_at" => now()
            ]);
        } catch (\Throwable $e) {
            return response()->json("Base de datos fallando", 500);
        }
        return response()->json("", 200);
    }
}
