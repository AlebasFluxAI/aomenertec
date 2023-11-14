<?php

namespace App\Http\Middleware\V1;

use App\Models\V1\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleSelectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->session()->get(User::SESSION_SINGLE_ROLE)) {
            return $next($request);
        }
        if (count(User::getUserRoles()) == 1) {
            $request->session()->put(User::SESSION_SINGLE_ROLE, true);
            $userRole = User::getUserRoles()[0]["rol"];
            $request->session()->put(User::SESSION_ROLE_SELECTED, $userRole);
            return $next($request);
        }

        $request->session()->put(User::SESSION_MULTI_ROLE, true);
        if (!$request->session()->exists(User::SESSION_ROLE_SELECTED)) {
            return redirect()->route("administrar.v1.seleccionar_role");
        }
        return $next($request);
    }
}
