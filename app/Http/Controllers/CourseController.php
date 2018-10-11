<?php

namespace App\Http\Controllers;

use App\Company;
use App\Course;
use App\CourseDay;
use App\Jobs\SendResponsibleInvitationEmail;
use App\Purchase;
use App\Services\FilterService;
use App\Services\GoogleMapsService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\UnauthorizedException;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;
use MetaTag;

class CourseController extends Controller
{

    /**
     * CourseController constructor.
     */
    public function __construct()
    {
        //$this->middleware('auth')->except(['show']);
    }

    /**
     * @param Request $request
     * @return array|static
     */
    public function coursesByDistance(Request $request)
    {
        $geotools = new Geotools();
        $my_position = null;

        if ($request->latitude != "null" && $request->longitude != "null") {
            $my_position = new Coordinate([$request->latitude, $request->longitude]);
        }

        $courses = Course::byActivate()->with('days')
            ->where('latitude', '<=', $my_position->getLatitude() + 0.10)
            ->where('latitude', '>=', $my_position->getLatitude() - 0.10)
            ->where('longitude', '<=', $my_position->getLongitude() + 0.10)
            ->where('longitude', '>=', $my_position->getLongitude() - 0.10)
            ->get();

        if ($my_position) {
            foreach ($courses as $course) {
                $course_coords = new Coordinate([$course->latitude, $course->longitude]);
                $distance = $geotools->distance()->setFrom($my_position)->setTo($course_coords);
                $course->distance_from_my_position = $distance->in('km')->vincenty();
                $course->distance_from_my_position = round($course->distance_from_my_position, 2);
            }

            return $courses->sortBy('distance_from_my_position')->values()->take(20);

        } else {
            return [];
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|LengthAwarePaginator|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $geotools = new Geotools();
        $my_position = null;

        if ($request->latitude != "null" && $request->longitude != "null") {
            $my_position = new Coordinate([$request->latitude, $request->longitude]);
        }

        $courses = Course::search($request->search_string)->get();


        if ($my_position) {
            foreach ($courses as $course) {
                $course_coords = new Coordinate([$course->latitude, $course->longitude]);
                $distance = $geotools->distance()->setFrom($my_position)->setTo($course_coords);
                $course->distance_from_my_position = $distance->in('km')->vincenty();
                $course->days;
            }
            $courses = $courses->sortBy('distance_from_my_position')->values()->take(100);
        } else {
            foreach ($courses as $course) {
                $course->days;
            }
            $courses->sortBy('seo_name_short');
        }

        MetaTag::set('title', 'Risultati di Ricerca');
        MetaTag::set('robots', 'noindex');

        if ($request->expectsJson())
            return $this->collection_paginate($courses, 50);

        return view('search.results', ['results' => $this->collection_paginate($courses, 100)]);
    }

    /**
     * @param $items
     * @param $per_page
     * @return LengthAwarePaginator
     */
    private function collection_paginate($items, $per_page)
    {
        $page = request()->get('page', 1);
        $offset = ($page * $per_page) - $per_page;

        return new LengthAwarePaginator(
            $items->forPage($page, $per_page)->values(),
            $items->count(),
            $per_page,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        //$this->authorize('create', Course::class);

        /** @var User $user */
        $user = Auth::user();
        $companies = $user->companies()->get();

        if (isset($request->company)) {
            $company = Company::where('slug', $request->company)->first();
        } else {
            $company = $user->companies->last();
        }

        if (empty($company) || !$user->hasThisCompany($company)) {
            throw new UnauthorizedException();
        }

        MetaTag::set('title', 'Registrazione Corso');

        return view('courses.create', ['navbarHidden' => true, 'company' => $company, 'companies' => $companies]);
    }

    /**
     * @param Course $course
     * @return $this
     */
    public function show(Course $course)
    {
        $data_course = Course::with(['services', 'days', 'company:id,name'])->findOrFail($course->id);
        $lessonPackages = $data_course->lessonPackages;
        $courseSubscriptions = $data_course->courseSubscriptions;

        foreach ($courseSubscriptions as $courseSubscription) {
            $courseSubscription->subscription;
        }

        $dayHours = [];

        foreach ($data_course->days as $day) {
            $start = explode(":", $day->start_time);
            $end = explode(":", $day->end_time);
            $dayHours[$day->dow] = $start[0] . ":" . $start[1] . " - " . $end[0] . ":" . $end[1] . " ";
        }

        $lessonPackageServices = [0 => [], 1 => []];
        $subscriptionServices = [0 => [], 1 => []];

        foreach ($data_course->lessonPackageServices as $s) {
            $lessonPackageServices[$s->pivot->is_excluded][] = $s->name;
        }

        foreach ($data_course->subscriptionServices as $s) {
            $subscriptionServices[$s->pivot->is_excluded][] = $s->name;
        }

        $company = $course->company;

        $courses = Course::with(['days'])->orderBy('id')->where("id", "<>", $course->id)->limit(10)->get();

        MetaTag::set('title', "{$course->company->public_name} - {$course->seo_name}");
        MetaTag::set('description', str_limit($course->description, 200, "..."));

        return view('courses.show', [
            'course' => $data_course,
            'courses' => $courses,
            'lessonPackages' => $lessonPackages,
            'courseSubscriptions' => $courseSubscriptions,
            'lessonPackageServices' => $lessonPackageServices,
            'subscriptionServices' => $subscriptionServices,
            "galleryImages" => $course->galleryImages,
            "dayHours" => $dayHours,
            'company' => $company
        ]);
    }

    /**
     * @param Course $course
     * @return $this
     */
    public function edit(Course $course)
    {
        $course->load('days', 'disabilities', 'services', 'subscriptions', 'subscriptionServices', 'lessonPackages', 'lessonPackageServices', 'galleryImages', 'documents')->toArray();
        $company = $course->company;
        $companies = $companies = Auth::user()->companies()->get();

        MetaTag::set('title', $course->getSeoNameAttribute());
        MetaTag::set('description', $course->description);

        return view('courses.create', ['navbarHidden' => true, 'company' => $company, 'companies' => $companies])->with('course', $course);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCourse(Request $request)
    {
        $request->validate([
            'course.address' => 'required',
            'course.addressComponents' => 'required',
            'course.latitude' => 'required',
            'course.longitude' => 'required',
            'course.federationId' => 'nullable|exists:federations,id',
            'course.sportId' => 'required|exists:sports,id'
        ]);

        $courseInput = $request->get('course');
        $placeDetails = GoogleMapsService::getDetailsFromAddressComponents($courseInput['addressComponents']);

        $company_id = $request->course['companyId'];

        $course = Course::createCourse($courseInput, $placeDetails, $company_id);

        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCourse(Request $request, Course $course)
    {
        $request->validate([
            'course.address' => 'required',
            'course.addressComponents' => 'required',
            'course.latitude' => 'required',
            'course.longitude' => 'required',
            'course.federationId' => 'nullable|exists:federations,id',
            'course.sportId' => 'required|exists:sports,id'
        ]);

        $courseInput = $request->get('course');

        $placeDetails = GoogleMapsService::getDetailsFromAddressComponents($courseInput['addressComponents']);
        $company_id = $request->course['companyId'];

        $course->updateCourse($courseInput, $placeDetails, $company_id);

        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCourseDates(Request $request, Course $course)
    {
        $request->validate([
            'courseDates' => 'required',
            'courseDays' => 'required'
        ]);

        $courseDates = $request->get('courseDates');
        $courseDays = $request->get('courseDays');

        $course->updateDates($courseDates);

        CourseDay::where('course_id', $course->id)->delete();
        foreach ($courseDays as $courseDay) {
            CourseDay::create([
                'day' => $courseDay['name'],
                'dow' => $courseDay['index'],
                'course_id' => $course->id,
                'start_time' => $courseDay['startTime']['hours'] . ':' . $courseDay['startTime']['minutes'],
                'end_time' => $courseDay['endTime']['hours'] . ':' . $courseDay['endTime']['minutes']
            ]);
        }
        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStepThree(Request $request, Course $course)
    {
        $request->validate([
            'audience' => 'required',
            'startAge' => 'required',
            'endAge' => 'required',
            'level' => ['required', Rule::in(['Principiante', 'Intermedio', 'Esperto', 'Professionista'])],
        ]);

        $course->updateAudience($request->audience);
        $course->updateGender($request->gender);

        $course->updateStepThree($request->all());

        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStepFour(Request $request, Course $course)
    {
        $image = $request->file('photo');
        $course->updateStepFour($request->all(), $image);

        if (isset($course->responsible_email) && preg_match("/([a-zA-Z0-9._-]+[@][a-zA-Z0-9._-]+)/", $course->responsible_email)) {
            SendResponsibleInvitationEmail::dispatch($course);
        }

        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDocuments(Request $request, Course $course)
    {
        $course->documents()->detach();

        foreach ($request->get('documents') as $document) {
            if ($document['checked'] == true) {
                $course->documents()->attach($document['id']);
            }
        }

        $course->updateSteps('step_9');
        $course->save();

        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCourseOffer(Request $request, Course $course)
    {
        $request->validate([
            'offer' => 'required'
        ]);

        $course->updateOffer($request->get('offer'));

        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCourseLesson(Request $request, Course $course)
    {
        $request->validate([
            'lesson' => 'required'
        ]);

        $course->updateLesson($request->get('lesson'));

        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCourseSubscriptions(Request $request, Course $course)
    {
        $request->validate([
            'subscriptions' => 'required|array|min:1'
        ]);
        $min_price = $course->min_price;
        foreach ($request->subscriptions as $subscription)
            if ($min_price == null || $subscription['price'] < $min_price)
                $min_price = $subscription['price'];
        if ($min_price != $course->min_price)
            $course->update(["min_price" => $min_price]);

        $course->updateSubscriptions($request->all());

        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCourseLessonPackages(Request $request, Course $course)
    {
        $request->validate([
            'lessonPackages' => 'required|array|min:1'
        ]);
        $min_price = $course->min_price;
        foreach ($request->lessonPackages as $lessonPackage)
            if ($min_price == null || $lessonPackage['price'] < $min_price)
                $min_price = $lessonPackage['price'];
        if ($min_price != $course->min_price)
            $course->update(["min_price" => $min_price]);

        $course->updateLessonPackages($request->all());

        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCourseDescription(Request $request, Course $course)
    {
        $request->validate([
            'text' => 'required'
        ]);

        $description = $request->get('text');
        $courseImage = $request->file('photo');
        $galleryImages = $request->file('gallery');

        $course->updateDescription($description, $courseImage, $galleryImages);

        return response()->json(['course' => $course]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function activateCourse(Request $request, Course $course)
    {
        $request->validate([
            'is_active' => 'required'
        ]);

        $isActive = $request->get('is_active');

        $course->updateIsActive($isActive);

        return response()->json(['is_active' => $course->is_active]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicateCourse(Request $request, Course $course)
    {
        $newCourse = $course->replicateCourse();
        $newCourse->load('days', 'disabilities', 'services', 'subscriptions', 'subscriptionServices', 'lessonPackages', 'lessonPackageServices', 'galleryImages')->toArray();

        return response()->json(['course' => $newCourse]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicateCourseIn(Course $course, Company $company)
    {
        $newCourse = $course->replicateCourse();
        $newCourse->load('days', 'disabilities', 'services', 'subscriptions', 'subscriptionServices', 'lessonPackages', 'lessonPackageServices', 'galleryImages')->toArray();

        $newCourse->update([
            'company_id' => $company->id
        ]);

        return response()->json(['course' => $newCourse]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return array
     */
    public function deleteCourse(Request $request, Course $course)
    {
        $company = $course->company;
        $course->delete();

        return ['redirect' => route('companies.dashboard', [$company])];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postBuy(Request $request)
    {
        $user = Auth::user();
        $cartElements = $user->carts;

        $purchase = DB::transaction(function () use ($user, $cartElements, $request) {
            $purchase = Purchase::createPurchaseForUser($user, $request);
            $token = $request->stripeToken;
            $user->saveCardInfos($token);
            $user->chargeWithStripe($purchase);

            return $purchase;
        });

        return redirect((route('summary', $purchase)));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function filterElements(Request $request)
    {
        $service = new FilterService($request->filters, $request->latitude, $request->longitude, $request->search_string);
        return $service->processFilters();
    }
}
