<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\V1\Subdomain;
use Illuminate\Http\Request;
use App\Models\V1\User;
use Illuminate\Support\Facades\Route;

class IndexController extends Controller
{
    public function index()
    {

        $subdomain = Route::input("subdomain");
        return match ($subdomain) {
            Subdomain::SUBDOMAIN_AOM => Subdomain::SUBDOMAIN_AOM > view('auth.login'),
            Subdomain::SUBDOMAIN_COENERGIA => view("auth.coenergia_login"),
            default => view('auth.login'),
        };
    }
}
