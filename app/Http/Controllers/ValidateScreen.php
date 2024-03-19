<?php

namespace App\Http\Controllers;

use App\Http\Controllers\V1\Controller;
use Illuminate\Http\Request;

class ValidateScreen extends Controller
{
    public function validateScreenSize(Request $request)
    {
        $isMobile = $request->input('isMobile');
        $request->header("isMobile", $isMobile);
        return response()->json(['status' => 'success', 'isMobile' => $isMobile]);
    }
}
