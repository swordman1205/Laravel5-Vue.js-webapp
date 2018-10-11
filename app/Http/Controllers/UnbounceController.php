<?php

namespace App\Http\Controllers;

use App\Company;
use App\Exceptions\ExistingEmailException;
use App\Jobs\SendCompanyRegisteredEmail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UnbounceController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveDataFromUnbounce(Request $request)
    {
        if (User::where('email', $request->email)) {
            return view('unbounce.existing_mail', ['error' => 'L\'indirizzo che hai inserito è già presente! Accedi al tuo pannello di controllo con la mail che hai già utilizzato']);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $company = Company::create([
            'name' => $request->nome_societa,
            'public_name' => $request->nome_societa,
            'registrant_id' => $user->id,
            'address' => $request->autocomplete,
            'house_number' => $request->street_number,
            'postal_code' => $request->postal_code,
            'municipality' => $request->locality,
            'country' => 'IT',
            'province' => $request->administrative_area_level_2,
            'region' => $request->administrative_area_level_1,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $company->users()->attach($user);

        Auth::loginUsingId($user->id);

        SendCompanyRegisteredEmail::dispatch($user, $company);

        return redirect(route('courses.create'));
    }
}