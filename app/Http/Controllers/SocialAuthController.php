<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function facebookRedirect(Request $request)
    {
        $redirect = $request->get('redirect');
        if (isset($redirect)) {
            $request->session()->flash('redirect', $redirect);
        }
        return Socialite::driver('facebook')
            ->fields(['first_name', 'last_name', 'email', 'birthday'])
            ->scopes(['email', 'user_birthday'])
            ->redirect();
    }

    public function googleRedirect(Request $request)
    {
        $redirect = $request->get('redirect');
        if (isset($redirect)) {
            $request->session()->flash('redirect', $redirect);
        }
        return Socialite::driver('google')->scopes(['openid', 'profile', 'email'])->redirect();
    }

    public function facebookCallback(Request $request)
    {
        $providerUser = Socialite::driver('facebook')->fields(['name', 'first_name', 'last_name', 'email', 'birthday'])->user();
        $user = User::emailOrFacebookIdExists($providerUser->email, $providerUser->id);

        if ($user) {
            if (is_null($user->facebook_id)) {
                $user->setFacebookId($providerUser->id);
            }
        } else {
            $birthday = null;
            if (isset($providerUser->user['birthday'])) {
                $birthday = $providerUser->user['birthday'];
            }
            if ($birthday) {
                $birthday = new DateTime($birthday);
            }
            $user = User::createFacebookUser($providerUser->user, $birthday);
        }
        return self::loginAndRedirect($request, $user);
    }

    public function googleCallback(Request $request)
    {
        $providerUser = Socialite::driver('google')->user();
        $user = User::emailOrGoogleIdExists($providerUser->email, $providerUser->id);

        if ($user) {
            if (is_null($user->google_id)) {
                $user->setGoogleId($providerUser->id);
            }
        } else {
            $birthday = null;
            if (isset($providerUser->user['birthday'])) {
                $birthday = $providerUser->user['birthday'];
            }
            if ($birthday) {
                $birthday = new DateTime($birthday);
            }
            $user = User::createGoogleUser($providerUser, $birthday);
        }
        return self::loginAndRedirect($request, $user);
    }

    public static function loginAndRedirect(Request $request, $user)
    {
        Auth::login($user);
        $redirect = $request->session()->get('redirect');
        if ($redirect) {
            return redirect($redirect);
        } else {
            return back()->with('success', "Ciao $user->first_name, sei autenticato ora");
        }
    }
}
