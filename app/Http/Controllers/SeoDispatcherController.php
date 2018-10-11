<?php

namespace App\Http\Controllers;

use App\Company;
use App\Course;
use App\Sport;
use Doctrine\Common\Inflector\Inflector;
use Illuminate\Support\Collection;
use MetaTag;

class SeoDispatcherController extends Controller
{
    /**
     * @param $name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCoursesBySportPage($name)
    {
        $sport_name = $this->revertSlug($name);
        $sport = Sport::where('name', $sport_name)->first();

        if ($sport) {
            $courses = Course::where('sport_id', $sport->id)->get();
        } else {
            $courses = new Collection();
        }


        return view('seo.per_sport')->with(compact('courses', 'sport'));
    }

    /**
     * @param string
     * @return string
     */
    public function revertSlug($slug)
    {
        return ucwords(str_replace('-', ' ', $slug));
    }

    /**
     * @param $name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCoursesByCity($name)
    {
        if ($name === "all") {
            $cities = array_filter(Course::pluck('municipality')->unique()->toArray());
            sort($cities, SORT_NATURAL | SORT_FLAG_CASE);

            return view('seo.all_cities', [
                'cities' => $cities
            ]);
        }

        $city_name = $this->revertSlug($name);
        $courses = Course::where('municipality', $city_name)->get();

        MetaTag::set('title', "{$courses->count()} Sport a $city_name | Solo su Orangogo");

        MetaTag::set('description', "Tutti gli Sport a $city_name. Trova quale sport praticare a $city_name solo su Orangogo - Il motore di ricerca degli sport");


        $courses = $courses->groupBy('sport_id');

        return view('seo.per_city')->with(compact('courses', 'city_name'));
    }

    /**
     * Search result page
     */
    public function showSearchResults()
    {
        $courses = Course::with(['days'])->orderBy('id')->limit(10)->get();

        MetaTag::set('title', 'Risultati di Ricerca');
        MetaTag::set('robots', 'noindex');

        return view('search.results', ['results' => $courses]);
    }

    /**
     * @param $old
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function redirectCompany($old)
    {
        $parts = explode("-", $old);

        $company = Company::where('old_id', $parts[0])->first();

        if ($company)
            return redirect(route('company.show', $company->slug));

        return abort(404);
    }

    /**
     * @param $old
     * @return \Illuminate\Http\RedirectResponse | null
     */
    public function redirectCourse($old)
    {
        $parts = explode("-", $old);

        $course = Course::where('old_id', $parts[0])->first();

        if ($course)
            return redirect(route('courses.show', $course->slug));

        return abort(404);
    }

    public function getComuneAndAll($targa, $comune, $istat)
    {
        return redirect(route('seo.comune', $comune));
    }

    public function getSportAndAll($targa, $comune, $istat, $descrKey)
    {
        return $this->getCoursesBySportAndCityPage($descrKey, $comune);
    }

    /**
     * @param $sportName
     * @param $cityName
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCoursesBySportAndCityPage($sportName, $cityName)
    {
        $sport_name = $this->revertSlug($sportName);
        $sport = Sport::where('name', $sport_name)->first();

        $city_name = $this->revertSlug($cityName);

        if ($sport) {
            $courses = Course::where('sport_id', $sport->id)->where('municipality', $city_name)->get();
        } else {
            $courses = new Collection();
        }

        return view('seo.per_city_sport')->with(compact('courses', 'city_name', 'sport'));
    }

    public function getSportAndCategoryAndAll($targa, $comune, $istat, $categoria = null, $descrKey)
    {
        return $this->getCoursesBySportAndCityPage($descrKey, $comune);
    }

    public function getSportAndCategoryAndAgeAndAll($targa, $comune, $istat, $categoria = null, $descrKey, $da_eta, $a_eta)
    {
        return $this->getCoursesBySportAndCityPage($descrKey, $comune);
    }

    public function getCoursesByProvince($provincia)
    {
        if ($provincia === "all") {
            $cities = array_filter(Course::pluck('province')->unique()->toArray());
            sort($cities, SORT_NATURAL | SORT_FLAG_CASE);

            return view('seo.all_cities', [
                'cities' => $cities
            ]);
        }

        $province_name = $this->revertSlug($provincia);
        $courses = Course::where('province', $province_name)->get();

        MetaTag::set('title', "{$courses->count()} Sport in pronvicia di $province_name | Solo su Orangogo");

        MetaTag::set('description', "Tutti gli Sport in provincia di $province_name. Trova quale sport praticare a $province_name solo su Orangogo - Il motore di ricerca degli sport");

        $courses = $courses->groupBy('sport_id');

        return view('seo.per_province')->with(compact('courses', 'province_name'));
    }
}
