<?php

namespace App\Http\Controllers;

use App\Company;
use App\Course;
use App\Jobs\SendCompanyContactformEmail;
use App\Jobs\SendContactformEmail;
use App\Jobs\SendReservationEmail;
use App\Jobs\SendResponsibleInvitationEmail;
use App\Jobs\SendUserRegisteredEmail;
use App\Mail\ConcactformMail;
use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MetaTag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courses = Course::byActivate()->with(['days'])->orderBy('created_at', 'desc')->get()->unique('company_id')->take(15)->values();

        return view('home', [
            'courses' => $courses,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function privacy()
    {
        MetaTag::set('title', 'Privacy');
        return view('privacy');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function terms()
    {
        MetaTag::set('title', 'Termini di Servizio');
        return view('terms');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        MetaTag::set('title', 'Chi Siamo');
        return view('about');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contactUs()
    {
        MetaTag::set('title', 'Contatta OrangoGo');
        return view('contact_us');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactform(Request $request) {
        if(!preg_match("/([a-zA-Z0-9._-]+[@][a-zA-Z0-9._-]+[a-zA-Z0-9_-]{2,4})/", $request->email) || empty($request->message)) {
            return response()->json(["message" => "field_error"], 506);
        }
        if (isset($request->company))
        {
            $company = Company::find($request->company);
            SendCompanyContactformEmail::dispatch($company,$request->email, $request->message);
        }
        else
            SendContactformEmail::dispatch($request->email, $request->message);
    }
}
