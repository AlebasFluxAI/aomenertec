<?php

namespace App\Http\Controllers;

use App\Http\Controllers\V1\Controller;
use App\Models\V1\Image;
use App\Models\V1\PqrMessage;
use App\Models\V1PqrMessage;
use Illuminate\Http\Request;

class testFile extends Controller
{
    public function upload(Request $request)
    {
        $pqr=PqrMessage::first();
        $pqr->fill($request->all());
        $pqr->update();

        return $pqr->images;
    }
}
