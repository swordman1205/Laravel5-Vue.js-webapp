<?php

namespace App\Http\Controllers;

use App\Disability;
use Illuminate\Http\Request;

class DisabilityController extends Controller
{
    public function getDisabilities()
    {
        $disabilities = Disability::all();

        return response()->json(['disabilities' => $disabilities]);
    }
}
