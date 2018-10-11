<?php

namespace App\Http\Controllers\Auth;

use App\Mail\UserRegisteredEmail;
use App\User;
use App\Http\Controllers\Controller;
use App\Jobs\SendUserRegisteredEmail;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MetaTag;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        MetaTag::set('title', 'Register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birthday' => 'required|date_format:"d/m/Y"',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'birthday' => Carbon::createFromFormat('d/m/Y', $data['birthday'])->format('Y-m-d'),
            'password' => bcrypt($data['password'])
        ]);
    }

    /**
     * Verify user
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verifyUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'token' => 'required|string'
        ]);
        try {
            $id = $request->user_id;
            $token = $request->token;
            $user = User::findOrFail($id);
            if ($user->email_verified) {
                return view('auth.verify-user', ['error' => 'This email is already verified!']);
            }
            $isVerified = Hash::check($user->email, $token);
            if ($isVerified) {
                $user->email_verified = true;
                $user->save();
                return view('auth.verify-user', ['error' => '']);
            } else {
                return view('auth.verify-user', ['error' => 'The token is invalid!']);
            }
        } catch (ModelNotFoundException $e) {
            return view('auth.verify-user', ['error' => $e->getMessage()]);
        }
    }

    public function registered(Request $request, $user)
    {
        SendUserRegisteredEmail::dispatch($user);

        if ($request->expectsJson())
            return $user;

        return false;
    }
}
