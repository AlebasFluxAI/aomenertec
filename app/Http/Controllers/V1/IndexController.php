<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\V1\Subdomain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class IndexController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            return redirect()->route("administrar.v1.perfil");
        }
        $subdomain = Route::input("subdomain");
        return match ($subdomain) {
            Subdomain::SUBDOMAIN_AOM => view('auth.login'),
            default => view("auth.subdomain_login"),
        };
    }

    public function forgotPassword()
    {
        return view('auth.passwords.email');
    }
}
