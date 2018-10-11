@extends('layouts.main')
@section('title', 'Course')
@section('content')
    <show-course :coursesprop="{{ $courses }}" inline-template v-cloak>
        <div class="container-fluid course-container">
            <div class="container-standard">
                <div class="row">
                    <div class="col-lg-8 p-b-20">
                        <div class="row">
                            <h1 class="course-container__course-name-seo">{{$course->seo_name}}</h1>
                            <div class="course-container__company-recipient-container">
                                <span class="course-container__address">
                                    <i class="text-orangogo2-color fa fa-map-marker-alt"></i>
                                    {{$course->getShortAddress()}}
                                </span>
                                <a class="course-container__company-name"
                                   href="{{'/societa_sportive/' . $company->slug}}">{{$company->name}}</a>
                                {{--<span class="course-container__recipient">Corso adatto dai {{$course->start_age}}
                                    - {{$course->end_age}} anni</span>--}}
                            </div>
                        </div>
                        <div class="row m-t-40">
                            <div class="col-lg-12 no-padding course-container__days-time-container">
                                <h3 class="font-weight-bold">Orari & Giorni</h3>
                                <div class="home-course-card__body__lessons row" style="font-size:0.8em">
                                    @foreach(['Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato','Domenica'] as $key => $val)
                                        <div class="m-t-10 p-r-10 m-r-10 border-right-grey">
                                            @if(isset($dayHours[ $key ]))
                                                <span class="text-uppercase font-weight-bold">{{ $val }}</span>
                                                <br>
                                                <span>{{ $dayHours[ $key ] }}</span>
                                            @else
                                                <span class="text-uppercase font-weight-bold text-color-lightgrey">{{ $val }}</span>
                                                <br>
                                                <span class="text-uppercase font-weight-bold text-color-lightgrey">chiuso</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row m-t-20 aligned-items-center">
                                    @if($course->start_date && $course->end_date)
                                        <span class="course-container__banner__period m-r-10">Periodo lezioni</span>
                                        <span> {{$course->getPeriod()}}</span>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                @if(count($course->subscriptions) > 0 && $course->has_subscriptions)
                                    <p style="margin-bottom:10px" class="p-t-30">Costi abbonamenti e pacchetti
                                        lezioni</p>
                                    @foreach($course->subscriptions as $subscription)
                                        <div class="p-t-10">Abbonamento {{ $subscription->name }} x <strong>{{ number_format($subscription->pivot->price,2,",",".") }} &euro;</strong>
                                        </div>
                                    @endforeach
                                @endif
                                @if(count($course->lessonPackages) > 0 && $course->has_lesson_packages)
                                    <div class="divider-gray-dark"></div>
                                    @foreach($course->lessonPackages as $lessonPackage)
                                        <div class="p-t-10">Pacchetto di {{ $lessonPackage->n_lessons}} lezioni x
                                            <strong>{{ number_format($lessonPackage->price,2,",",".") }} &euro;</strong>
                                        </div>
                                    @endforeach
                                @endif
                                @if(count($course->disabilities) > 0)
                                    <p style="margin-bottom:10px" class="p-t-30">Disabilit&agrave; Supportate</p>
                                    {{ $course->disabilities[0]->name }}@for($i=1;$i<count($course->disabilities); $i++)
                                        , {{ $course->disabilities[$i]->name }}@endfor
                                @endif
                            </div>
                            <h2 class="p-t-20">Descrizione {{$course->seo_name_short}}</h2>
                            @if($course->description)
                                <p class="col-12 p-0 text-justify">{!!  nl2br($course->description) !!}</p>
                            @else
                                <p class="col-12 p-0 text-justify"><i>Ancora nessuna descrizione presente per questo corso</i></p>
                            @endif
                            <div class="mt-4">&nbsp;
                            </div>

                            <google-map ref="mapRef"
                                        :zoom="15"
                                        :center="{lat:{{$course->latitude}},lng:{{$course->longitude}} }"
                                        map-type-id="terrain"
                                        style="width: 100%; height: 200px">
                                <google-marker
                                        :position="{lat:{{$course->latitude}},lng:{{$course->longitude}} }"
                                        :clickable="true"
                                        :draggable="true"
                                ></google-marker>
                            </google-map>

                        </div>
                        <div class="col-12 m-t-30 p-0">
                            <div class="row">
                                @if(count($subscriptionServices[1]+$lessonPackageServices[1]) > 0)
                                    <div class="col-lg-6 p-0">
                                        <h3 class="p-t-30">Incluso</h3>
                                        @foreach($subscriptionServices[1] as $subscriptionService)
                                            <div class="p-t-10">- {{$subscriptionService}} </div>
                                        @endforeach
                                        @foreach($lessonPackageServices[1] as $lessonPackageService)
                                            <div class="p-t-10">- {{$lessonPackageService}} </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if(count($subscriptionServices[0]+$lessonPackageServices[0]) > 0)
                                    <div class="col-lg-6 p-0">
                                        <h3 class="p-t-30">Non Incluso</h3>
                                        @foreach($subscriptionServices[0] as $subscriptionService)
                                            <div class="p-t-10">- {{$subscriptionService}} </div>
                                        @endforeach
                                        @foreach($lessonPackageServices[0] as $lessonPackageService)
                                            <div class="p-t-10">- {{$lessonPackageService}} </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if(count($course->services) > 0)
                                    <div class="col-lg-12 p-0">
                                        <h3 class="p-t-30">Servizi Disponibili</h3>
                                        <div class="row">
                                            @foreach($course->services as $service)
                                                <div class="col-6 p-t-10 p-0">- {{$service->name}} </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($course->lesson_equipments)
                                    <div class="col-lg-12 p-0">
                                        <h3 class="p-t-30">Abbigliamento</h3>
                                        <div class="row">
                                            <div class="col-6 p-t-10 p-0"><p>{{$course->lesson_equipments}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 p-b-20">
                        <course-reservation-box
                                :course="{{$course}}"
                                :subscriptionsprop="{{$courseSubscriptions}}"
                                :lessonpackagesprop="{{$lessonPackages}}"
                                :galleryimagesprop="{{$galleryImages}}"
                        ></course-reservation-box>
                    <!--reservation-box :course="{{$course}}">
                        </reservation-box-->
                    </div>
                </div>
                <div class="col-lg-12 p-b-20">
                    <h3 class="p-t-20">Potrebbero interessarti anche</h3>
                    <home-carousel :items="courseList" :count-prop="4" v-if="courseList.length > 0">
                        {{--<template slot-scope="{item}">
                            <home-course-card :course="item">
                            </home-course-card>
                        </template>--}}
                    </home-carousel>
                </div>
            </div>
        </div>
    </show-course>
@endsection

@section('scripts')
    <script>
    </script>
@endsection