<?php

namespace App\Http\Middleware\V1;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
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
        if (Auth::guest()) {
            return redirect('/');
        }
        if (!$request->user()->can($permission)) {
            abort(403);
        }
        return $next($request);
    }
}
