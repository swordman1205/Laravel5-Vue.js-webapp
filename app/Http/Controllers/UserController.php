<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MetaTag;
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProfile(Request $request)
    {
        $user = $request->user();
        $reservations = $user->reservations;
        $purchaseElements = $user->purchaseElements;

        MetaTag::set('title', 'Profilo');

        return view('users.profile', ['user' => $user, 'reservations' => $reservations, 'purchases' => $purchaseElements]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request, User $user)
    {
        try {
            return response()->json(['user' => $user->load(['role'])], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birthday' => 'required|date'
        ]);
        try {
            $user->update($request->all());
            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPassword(Request $request)
    {

        $request->validate([
            'password' => 'required|min:6'
        ]);
        try {
            $password = $request->get('password');
            $user = $request->user();

            $user->updatePassword($password);

            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setEmail(Request $request)
    {

        $email = $request->get('email');
        $user = $request->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        $user->updateEmail($email);

        return response()->json(['user' => $user], 200);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmail(Request $request, User $user)
    {
        $request->validate([
            'currentEmail' => 'required|email|exists:users,email',
            'newEmail' => 'required|email|unique:users,email'
        ]);
        try {
            $email = $request->get('newEmail');

            $user->updateEmail($email);

            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'currentPassword' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    return $fail('Wrong password');
                }
            }],
            'newPassword' => 'required|min:6'
        ]);
        try {
            $password = $request->get('newPassword');
            $user->updatePassword($password);

            return response()->json(['user' => $user], 200);
        } catch
        (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function getFriends(Request $request){
        $user = $request->user();
        return response()->json(['friends' => $user->friends()->get()], 200);
    }
}
