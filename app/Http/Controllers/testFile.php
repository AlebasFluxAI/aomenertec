<?php

namespace App\Http\Controllers;

use App\Http\Controllers\V1\Controller;
use App\Models\V1\Image;
use Illuminate\Http\Request;

class testFile extends Controller
{
    public function upload(Request $request)
    {
        $path = $request->file('image')->store('images', 's3');
        return $path;
    }
}
