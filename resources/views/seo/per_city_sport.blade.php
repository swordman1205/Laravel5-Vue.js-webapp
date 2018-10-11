@extends('layouts.main')
@section('title', 'About us')
@section('content')
    <div>
        <b-container>
            <b-row class="w-100">
                <b-col cols="12" md="">
                    <h2 class="font-weight-bold">Corsi di {{$sport? $sport->name: ''}} a {{$city_name}}</h2>
                </b-col>
            </b-row>

            @if($courses)
                @foreach($courses as $course)
                    <div class="courses_seo_results">
                        <b-row class="mt-4 border-bottom">
                            <b-row class="w-100">
                                <b-col cols="12" md="3">
                                    <a href="/corsi/{{$course->slug}}"><img class="course-image"
                                                                            src="{{$course->course_image}}"/></a>
                                </b-col>
                                <b-col cols="12" md="9">
                                    <i class="company-icon mr-2 fas fa-map-marker-alt"></i>
                                    <span class="text-uppercase company-dashboard__course-address">{{$course->route}}
                                        @if(isset($course->house_number))
                                            <span> {{$course->house_number}}</span>
                                        @endif
                                        @if(isset($course->province))
                                            <span>, {{$course->province}}</span>
                                        @endif
                        </span>
                                    <h2 class="font-weight-bold">{{$course->name}}</h2>
                                    <b-row>
                                        <span class="float-left p-r-10 border-right-grey"><b>{{$course->sport? $course->sport->name: ''}}</b></span>
                                        <span class="float-left p-r-10 p-l-10 border-right-grey">{{$course->start_age}}
                                            - {{$course->end_age}} anni</span>
                                        <span class="float-left p-r-10 p-l-10 border-right-grey">{{$course->level}}</span>
                                        <span class="float-left p-l-10">Periodo {{$course->start_date? $course->start_date->format('d/m/Y'): ''}}
                                            - {{$course->end_date? $course->end_date->format('d/m/Y'): ''}}</span>
                                    </b-row>
                                    <b-row class="mt-3">
                                        <div class="d-inline-block">
                                            {{--<div v-for="day in activeDays[courseIndex]" class="company-day text-center"
                                                 :class="getDayClass(day)">@{{ day.shortName }}
                                            </div>--}}
                                        </div>

                                    </b-row>
                                </b-col>
                            </b-row>
                        </b-row>
                    </div>
                @endforeach
            @endif
        </b-container>
    </div>
@endsection