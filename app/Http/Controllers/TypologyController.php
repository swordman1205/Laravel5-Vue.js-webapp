<?php

namespace App\Http\Controllers;

use App\Typology;
use Illuminate\Http\Request;

class TypologyController extends Controller
{
    public function getTypologies()
    {
        $typologies = Typology::all();

        return response()->json(['typologies' => $typologies]);
    }
}
