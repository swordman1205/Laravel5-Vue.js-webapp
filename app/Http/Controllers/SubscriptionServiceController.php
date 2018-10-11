<?php

namespace App\Http\Controllers;

use App\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionServiceController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getServices()
    {
        $services = SubscriptionService::all();

        return $services;
    }
}
