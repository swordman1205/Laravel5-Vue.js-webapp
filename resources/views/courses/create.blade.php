@extends('layouts.main', ['navbarToInclude'=> 'navbar-course-edit' ])
@section('title', 'Create course')
@section('content')

    <registration course-object="{{isset($course) ? $course : null}}" inline-template v-cloak>
        <div>
            <div class="container-fluid registrations-steps">
                <div>
                    @if(isset($course))
                        <b-card no-body>
                            <b-tabs card v-model="tabIndex">
                                @endif
                                <b-progress class="registration-progress" :value="counter" :max="max"></b-progress>
                                @if(isset($course))
                                    <b-tab>
                                        <template slot="title">
                                            <span class="tab-title">Sport e indirizzo</span>
                                        </template>
                                        @endif
                                            @include('courses.steps.step_one')
                                        @if(isset($course))
                                            </b-tab>
                                            <b-tab>
                                            <template slot="title">
                                                <span class="tab-title">Periodo e orari</span>
                                            </template>
                                        @endif
                                                @include('courses.steps.step_two')
                                        @if(isset($course))
                                    </b-tab>
                                    <b-tab>
                                        <template slot="title">
                                            <span class="tab-title">Età e livello</span>
                                        </template>
                                        @endif
                                            @include('courses.steps.step_three')
                                        @if(isset($course))
                                    </b-tab>
                                    <b-tab>
                                        <template slot="title">
                                            <span class="tab-title">Disabilità e servizi</span>
                                        </template>
                                        @endif
                                            @include('courses.steps.step_four')
                                        @if(isset($course))
                                    </b-tab>
                                    <b-tab>
                                        @endif
                                        <template slot="title">
                                            <span class="tab-title">Descrizione e foto</span>
                                        </template>
                                            @include('courses.steps.step_five')
                                        @if(isset($course))
                                    </b-tab>
                                    <b-tab>
                                        <template slot="title">
                                            <span class="tab-title">Lezione di prova</span>
                                        </template>
                                        @endif
                                            @include('courses.steps.step_six')
                                        @if(isset($course))
                                    </b-tab>
                                    <b-tab>
                                        <template slot="title">
                                            <span class="tab-title">Modalità di vendita</span>
                                        </template>
                                        @endif
                                            @include('courses.steps.step_seven')
                                        @if(isset($course))
                                    </b-tab>
                                    <b-tab id="tab-eight">
                                        <template slot="title">
                                            <span class="tab-title">Pacchetti di lezioni</span>
                                        </template>
                                        @endif
                                            @include('courses.steps.step_eight')
                                        @if(isset($course))
                                    </b-tab>
                                    <b-tab id="tab-nine">
                                        <template slot="title">
                                            <span class="tab-title">Abbonamenti</span>
                                        </template>
                                        @endif
                                            @include('courses.steps.step_nine')
                                        @if(isset($course))
                                    </b-tab>
                                    {{--<b-tab>
                                        <template slot="title">
                                            <span class="tab-title">Modalità di vendita</span>
                                        </template>
                                        @endif
                                            @include('courses.steps.step_ten')
                                        @if(isset($course))
                                    </b-tab>--}}
                            </b-tabs>
                        </b-card>
                    @endif
                </div>
            </div>
        </div>
    </registration>

@endsection
@section('script')
    <script>
        window.baseUrl = '{{env('APP_URL')}}'
        window.googleMapsAPI = '{{env('GOOGLE_MAPS_API')}}'
    </script>
@endsection
