<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Models\V1\User;

class HomeController extends Controller
{
    public function index()
    {
        return redirect('/v1/inicio');
    }
}
