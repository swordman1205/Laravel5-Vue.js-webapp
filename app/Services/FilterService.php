<?php

namespace App\Services;

use App\Course;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class FilterService
{
    protected $filters;
    protected $searchString;
    protected $latitude;
    protected $longitude;

    /**
     * FilterService constructor.
     * @param $filters
     */
    public function __construct($filters, $latitude, $longitude, $searchString)
    {
        $this->filters = $filters;
        $this->search_string = $searchString;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function processFilters()
    {
        $search_string_courses = Course::search($this->search_string)->get()->pluck('id');

        $courses = Course::byAge($this->filters['age'])
            ->byActivate()
            ->byFreeTrial(isset($this->filters['freeTrial']) ? $this->filters['freeTrial'] : [])
            ->byDistance($this->filters['distance'], $this->latitude, $this->longitude)
            ->bySport($this->filters['sport'])
            ->byDisabilities($this->filters['disability'])
            //->byPrice($this->filters['price'])
            ->byDays(isset($this->filters['days']) ? $this->filters['days'] : [])
            ->byTime($this->filters['hours']['startTime'], $this->filters['hours']['endTime'])
            ->whereIn('id', $search_string_courses)
            ->with('days')
            ->get();

        foreach ($courses as $course) {
            $course->calculateDistancesFromHere($this->latitude, $this->longitude);
        }

        $courses = $courses->sortBy('distance_from_my_position')->values();

        return $courses;
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

        return $courses->sortBy('distance_from_my_position')->values()->take(100);
    }
}