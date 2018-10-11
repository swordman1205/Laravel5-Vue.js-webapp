<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Jobs\SendReservationEmail;
use App\Reservation;
use Illuminate\Http\Request;
Use MetaTag;

class ReservationController extends Controller
{

    /**
     * ReservationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Reservation $reservation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showConfirmation(Reservation $reservation)
    {
        MetaTag::set('title', 'Prenotazioni');
        return view('reservations.confirmations', ['reservation' => $reservation]);
    }

    /**
     * @param Reservation $reservation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showConfirm(Reservation $reservation)
    {
        MetaTag::set('title', 'Prenotazioni');
        return view('reservations.confirm', ['reservation' => $reservation]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservations(Request $request)
    {
        $reservations = $request->user()->reservations()->orderBy('updated_at', 'desc')->get();
        return response()->json(['reservations' => $reservations], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeReservation(Request $request)
    {
        /*
        course_id:7
        date:14
        datetime:"2018-08-14 19:30"
        end_time:"20:30"
        long_day:"Martedì"
        month:"Agosto"
        month_short:"Ago"
        short_day:"MAR"
        start_time:"19:30"
    */
        $request->validate([
            'reservation.course_id' => 'required|exists:courses,id',
            'reservation.datetime' => 'required'
        ]);

        $reservation = Reservation::createReservation($request->get('reservation'), $request->user());
        return response()->json(['reservation' => $reservation], 200);
    }

    /**
     * @param Request $request
     * @param Reservation $reservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveConfirmation(Request $request, Reservation $reservation)
    {
        $request->validate([
            'sportsman.firstName' => 'required',
            'sportsman.lastName' => 'required',
            'sportsman.birthday.year' => 'required',
            'sportsman.birthday.month' => 'required',
            'sportsman.birthday.day' => 'required',
            'sportsman.email' => 'required|email',
            'saveAsFriend' => 'required'
        ]);
        $saveFriend = $request->get('saveAsFriend');
        $sportsman = $request->get('sportsman');
        $reservation->saveConfirmation($sportsman);
        if($saveFriend){
            Friend::saveFriend($request->user()->id, $sportsman);
        }
        $reservation->confirm();
        SendReservationEmail::dispatch($reservation, $reservation->user, $reservation->course);
        return response()->json(['reservation' => $reservation], 200);
    }

    /**
     * @param Request $request
     * @param Reservation $reservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmReservation(Request $request, Reservation $reservation)
    {
        $reservation->confirm();
        return response()->json(['Success'], 200);
    }
}
