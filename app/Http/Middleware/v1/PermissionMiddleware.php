<?php

namespace App\Http\Middleware\v1;

use Closure;
use Illuminate\Http\Request;
use Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (Auth::guest()) {
            return redirect('/login');
        }
        if (! $request->user()->can($permission)) {
            abort(403);
        }
        return $next($request);
    }
}
