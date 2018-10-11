<?php

namespace App\Http\Controllers;

use App\Sport;
use Illuminate\Http\Request;

class SportController extends Controller
{
    public function getSport(Request $request, Sport $sport){
        return response()->json(['sport' => $sport]);
    }

    public function searchSports(Request $request)
    {
        $search = $request->get('q');
        $sports = Sport::searchSports($search);

        return response()->json(['sports' => $sports]);
    }
}
