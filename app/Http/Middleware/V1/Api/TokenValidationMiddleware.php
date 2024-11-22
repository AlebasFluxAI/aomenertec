<?php

namespace App\Http\Middleware\V1\Api;

use App\Models\V1\Api\ApiKey;
use Closure;

class TokenValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @aram \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader(ApiKey::API_HEADER)) {
            abort(401, "Error al validar api key de cliente");
        }
        $apiKey = ApiKey::where("api_key", $request->header(ApiKey::API_HEADER))->first();
        if (!$apiKey or !$apiKey->isValid()) {
            abort(401, "Error al validar api key de cliente");
        }
        return $next($request);
    }
}
