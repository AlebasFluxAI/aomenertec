<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Models\v1\User;

class HomeController extends Controller
{
    public function index()
    {
        return redirect('/administrar/v1');
    }
}
