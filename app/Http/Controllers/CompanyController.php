<?php

namespace App\Http\Controllers;

use App\Company;
use App\Course;
use App\Http\Requests\StoreCompany;
use App\Jobs\SendCompanyRegisteredEmail;
use App\Jobs\SendReservationEmail;
use App\Jobs\SendUserRegisteredEmail;
use App\Reservation;
use App\Services\GoogleMapsService;
use App\Sport;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use MetaTag;

class CompanyController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $companies = $user->companies()->get();

        MetaTag::set('title', 'Società');
        return view('companies.index', ['user' => $user, 'companies' => $companies]);
    }

    /**
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard(Company $company)
    {
        $user = Auth::user();
        $reservations = $company->reservations()->confirmed()->get();

        foreach ($reservations as $reservation) {
            $reservation->course;
            $reservation->user;
        }

        $purchaseElements = $company->purchaseElements;

        MetaTag::set('title', $company->name);
        MetaTag::set('description', $company->description);

        return view('companies.dashboard', [
            'user' => $user,
            'company' => $company,
            'reservations' => $reservations,
            'purchases' => $purchaseElements
        ]);
    }

    /**
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyInfo(Company $company)
    {
        $courses = $company->courses()->with('days')->get();
        $images = $company->galleryImages()->get();
        $sports = $courses->pluck('sport.name')->unique();

        MetaTag::set('title', $company->public_name);
        MetaTag::set('description', str_limit($company->description, 200, '...'));

        return view('companies.detail', [
            'company' => Company::with(['federation', 'legalForm'])->findOrFail($company->id),
            'courses' => $courses,
            'images' => $images,
            'sports' => $sports
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompany(Request $request, Company $company)
    {
        $company->load(['galleryImages']);
        return response()->json(['company' => $company], 200);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCompany(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'legalFormId' => 'required|exists:legal_forms,id',
            'typologyId' => 'required|exists:typologies,id',
            'email' => 'required|email',
            'phoneNumber' => 'required',
            'fiscalCode' => 'required',
            'address' => 'required',
        ]);


        if ($request->logoPath != null) {
            $logo = '';
        } else {
            $logo = $request->file('logo');
        }

        $galleryImages = $request->file('gallery');
        $oldGalleryImages = [];

        if ($request->gallery != null) {
            foreach ($request->gallery as $image) {
                if (!$image instanceof UploadedFile) {
                    $oldGalleryImages[] = $image;
                }
            }
        }

        $statuteFile = $request->file('statute-file');
        $privacyPolicyFile = $request->file('privacy-policy-file');
        $company->updateCompany($request->all(), $logo, $galleryImages, $oldGalleryImages, $statuteFile, $privacyPolicyFile);

        return response()->json(['company' => $company], 200);
    }

    /**
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourses(Company $company)
    {
        $courses = $company->courses()->with('days')->with('sport')->get();

        $sports = Sport::byCourses($courses);

        return response()->json(['courses' => $courses, 'sports' => $sports], 200);
    }

    /**
     * @param StoreCompany $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCompany(StoreCompany $request)
    {
        $requestCompany = $request->all();
        $requestUser = $requestCompany['user'];
        $requestUser['password'] = Hash::make(time() . $requestUser['email']);
        $user = User::create($requestUser);

        $requestCompany['registrant_id'] = $user->id;
        $company = Company::create($requestCompany);
        $company->users()->attach($user);

        Auth::loginUsingId($user->id);

        return response()->json([
            'company' => $company,
            'user' => $user], 201);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function registerCompany(Request $request)
    {
        $company = DB::transaction(function () use ($request) {
            if (Auth::check())
                $user = Auth::user();

            else {
                $user = new User($request->user);
                $user->password = bcrypt($request->user['password']);
                $user->save();
                Auth::login($user);
            }

            $company = new Company($request->company);
            $company->registrant_id = $user->id;
            $company->fillAddressData($request->companyAddress);

            $user->companies()->attach($company);

            SendCompanyRegisteredEmail::dispatch($user, $company);

            return $company;
        });

        return $company->toJson();
    }

    /**
     * @param Request $request
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCompanyName(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $name = $request->get('name');

        if (!isset($company)) {
            return response()->json(['error' => 'No company with that id'], 400);
        }
        if (!$company->hasUser($request->user())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $company->updateName($name);

        return response()->json(['company' => $company], 200);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCompanyAddress(Request $request, Company $company)
    {
        $request->validate([
            'address' => 'required',
            'addressComponents' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        $address = $request->get('address');
        $addressComponents = $request->get('addressComponents');
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');

        if (!isset($company)) {
            return response()->json(['error' => 'No company with that id'], 400);
        }

        if (!$company->hasUser($request->user())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $company->updateAddress($address);

        $placeDetails = GoogleMapsService::getDetailsFromAddressComponents($addressComponents);
        $company->updateGoogleAddress($placeDetails, $latitude, $longitude);


        return response()->json(['company' => $company], 200);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddSports(Request $request, Company $company)
    {
        $request->validate([
            'sports' => 'required|array|min:1',
            'sports.id' => 'exists:sports,id'
        ]);
        try {
            if (!$company->hasUser($request->user())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            //detach all sports before adding new one
            $company->sports()->detach();

            $sports = $request->get('sports');
            $company->sports()->attach(array_column($sports, 'id'));

            return response()->json(['added' => true], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
