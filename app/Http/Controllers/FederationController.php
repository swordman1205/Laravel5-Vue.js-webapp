<?php

namespace App\Http\Controllers;

use App\Federation;
use Illuminate\Http\Request;

class FederationController extends Controller
{
    public function getFederations()
    {
        $federations = Federation::all();

        return response()->json(['federations' => $federations]);
    }
}
