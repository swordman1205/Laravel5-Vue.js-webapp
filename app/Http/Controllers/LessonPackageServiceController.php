<?php

namespace App\Http\Controllers;

use App\LessonPackageService;
use Illuminate\Http\Request;

class LessonPackageServiceController extends Controller
{
    public function getServices()
    {
        $services = LessonPackageService::all();

        return response()->json(['services' => $services]);
    }
}
