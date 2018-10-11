<?php
/**
 * Created by PhpStorm.
 * User: joy_f
 * Date: 2018-06-05
 * Time: 08:50
 */

namespace App\Services;

use SKAgarwal\GoogleApi\PlacesApi;

class GoogleMapsService
{
    public static function autoCompletePlace($placeQuery)
    {
        $googlePlaces = new PlacesApi(env('GOOGLE_MAPS_API'));
        $places = $googlePlaces->placeAutocomplete($placeQuery);

        return $places;
    }

    public static function getDetailsFromAddressComponents($addressComponents)
    {
        $addressDetails['route'] = null;
        $addressDetails['house_number'] = null;
        $addressDetails['postal_code'] = null;
        $addressDetails['municipality'] = null;
        $addressDetails['province'] = null;
        $addressDetails['region'] = null;
        $addressDetails['country'] = null;
        
        foreach ($addressComponents as $addressComponent) {
            if ($addressComponent['types'][0] === 'route') {
                $addressDetails['route'] = $addressComponent['long_name'];
            }
            if ($addressComponent['types'][0] === 'street_number') {
                $addressDetails['house_number'] = $addressComponent['long_name'];
            }
            if ($addressComponent['types'][0] === 'postal_code') {
                $addressDetails['postal_code'] = $addressComponent['short_name'];
            }
            if ($addressComponent['types'][0] === 'administrative_area_level_3') {
                $addressDetails['municipality'] = $addressComponent['long_name'];
            }
            if ($addressComponent['types'][0] === 'administrative_area_level_2') {
                $addressDetails['province'] = $addressComponent['short_name'];
            }
            if ($addressComponent['types'][0] === 'administrative_area_level_1') {
                $addressDetails['region'] = $addressComponent['long_name'];
            }
            if ($addressComponent['types'][0] === 'country') {
                $addressDetails['country'] = $addressComponent['short_name'];
            }
        }
        return $addressDetails;
    }
}