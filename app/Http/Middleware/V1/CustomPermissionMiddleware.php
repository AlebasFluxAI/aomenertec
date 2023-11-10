<?php

namespace App\Http\Middleware\V1;

use App\Models\V1\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CustomPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!array_intersect([$permission], User::getUserModel()->getPermissions())) {
            abort(403);
        }
        if (!Auth::user()->enabled) {
            abort(403);
        }
        return $next($request);
    }
}
