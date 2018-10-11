<?php

namespace App\Http\Controllers;

use App\Services\GoogleMapsService;
use Illuminate\Http\Request;

class GoogleMapsController extends Controller
{
    public function autoCompletePlaces(Request $request)
    {
        $request->validate([
            'place' => 'required'
        ]);
        try {
            $placeInput = $request->input('place');
            $places = GoogleMapsService::autoCompletePlace($placeInput);
            if (isset($places['status']) && $places['status'] === 'ZERO_RESULTS') {
                return response()->json(['error' => 'Invalid address'], 400);
            }
            return response()->json([$places]);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

}
